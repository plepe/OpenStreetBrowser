-- create boundaries
drop table if exists planet_osm_boundaries;
create table planet_osm_boundaries(rel_id int4 not null, place_id int4 null, admin_level text, name text, guess boolean default false);
SELECT AddGeometryColumn('planet_osm_boundaries', 'way', 900913, 'MULTILINESTRING', 2);
SELECT AddGeometryColumn('planet_osm_boundaries', 'poly', 900913, 'POLYGON', 2);

insert into planet_osm_boundaries select relation_id, null, planet_osm_rels.admin_level, planet_osm_rels.name, false, st_multi(st_linemerge(st_collect(way))), CASE WHEN IsClosed(st_multi(st_linemerge(st_collect(way)))) THEN MakePolygon(LineMerge(st_multi(st_linemerge(st_collect(way))))) END  from relation_members join planet_osm_line on osm_id=member_id and member_type='W' join planet_osm_rels on relation_id=id where planet_osm_rels.type in ('boundary') and planet_osm_rels.boundary='administrative' group by relation_id, planet_osm_rels.name, planet_osm_rels.admin_level;
insert into planet_osm_boundaries select id, null, planet_osm_rels.admin_level, planet_osm_rels.name, false, Multi(Boundary(way)), way from planet_osm_rels join planet_osm_polygon on planet_osm_rels.id=-planet_osm_polygon.osm_id where planet_osm_rels.type in ('multipolygon') and planet_osm_rels.boundary='administrative';
update planet_osm_boundaries set poly=ST_ConvexHull(way), guess=true where poly is null and geometrytype(ST_ConvexHull(way))='POLYGON';

create index planet_osm_boundaries_name on planet_osm_boundaries(name);
create index planet_osm_boundaries_way on planet_osm_boundaries using gist(way);
create index planet_osm_boundaries_poly on planet_osm_boundaries using gist(poly);

-- insert into planet_osm_boundaries select rel.id, rel.admin_level, rel.name, 
-- ST_Boundary(ST_Union(way)) from planet_osm_rels rel, planet_osm_line where osm_id=any(rel.ways_parts) group by rel.id, rel.admin_level, rel.name;

-- select b.name, to_intarray(b.rel_id), to_textarray(b.admin_level), p.osm_id, p.place from planet_osm_boundaries b join planet_osm_point p on b.name=p.name and p.way@b.poly and p.place is not null group by p.osm_id, b.name, p.place order by b.name;
update planet_osm_boundaries set place_id=member_id, rel_id=relation_id from relation_members where member_role='label' and member_type='N' and rel_id=relation_id;
update planet_osm_boundaries set place_id=(select osm_id from planet_osm_point where place in ('country') and planet_osm_boundaries.name=planet_osm_point.name and planet_osm_point.way@@planet_osm_boundaries.poly and Within(planet_osm_point.way, planet_osm_boundaries.poly) limit 1) where admin_level='2' and place_id is null;
update planet_osm_boundaries set place_id=(select osm_id from planet_osm_point where place in ('state') and planet_osm_boundaries.name=planet_osm_point.name and planet_osm_point.way@planet_osm_boundaries.poly and Within(planet_osm_point.way, planet_osm_boundaries.poly) limit 1) where admin_level='4' and place_id is null;
update planet_osm_boundaries set place_id=(select osm_id from planet_osm_point where place in ('region') and planet_osm_boundaries.name=planet_osm_point.name and planet_osm_point.way@planet_osm_boundaries.poly and Within(planet_osm_point.way, planet_osm_boundaries.poly) limit 1) where admin_level='6' and place_id is null;
update planet_osm_boundaries set place_id=(select osm_id from planet_osm_point where place in ('village', 'town', 'city') and planet_osm_boundaries.name=planet_osm_point.name and planet_osm_point.way@planet_osm_boundaries.poly and Within(planet_osm_point.way, planet_osm_boundaries.poly) limit 1) where admin_level='8' and place_id is null;
update planet_osm_boundaries set place_id=(select osm_id from planet_osm_point where place in ('suburb') and planet_osm_boundaries.name=planet_osm_point.name and planet_osm_point.way@planet_osm_boundaries.poly and Within(planet_osm_point.way, planet_osm_boundaries.poly) limit 1) where admin_level='10' and place_id is null;

insert into planet_osm_colls select osm_id, 'boundary', array['name', 'place', 'admin_level'], array[planet_osm_point.name, planet_osm_point.place, planet_osm_boundaries.admin_level] from planet_osm_boundaries join planet_osm_point on planet_osm_boundaries.place_id=planet_osm_point.osm_id;
insert into coll_members select osm_id, osm_id, 'N', 'label' from planet_osm_boundaries join planet_osm_point on planet_osm_boundaries.place_id=planet_osm_point.osm_id;
insert into coll_members select osm_id, rel_id, 'R', '' from planet_osm_boundaries join planet_osm_point on planet_osm_boundaries.place_id=planet_osm_point.osm_id;

drop table if exists planet_osm_boundaries_only;
create table planet_osm_boundaries_only (
osm_id int4,
admin_level int4
);
SELECT AddGeometryColumn('planet_osm_boundaries_only', 'way', 900913, 'LINESTRING', 2);

insert into planet_osm_boundaries_only
select osm_id, (CASE 
    WHEN min(cast (r.admin_level as int)) is null THEN (array_sort(array_toint(regexp_split_to_array(l.admin_level, ' *; *'))))[1]
    WHEN (array_sort(array_toint(regexp_split_to_array(l.admin_level, ' *; *'))))[1] is null THEN min(cast (r.admin_level as int)) 
    WHEN (array_sort(array_toint(regexp_split_to_array(l.admin_level, ' *; *'))))[1]<min(cast (r.admin_level as int))
      THEN (array_sort(array_toint(regexp_split_to_array(l.admin_level, ' *; *'))))[1]
    ELSE min(cast (r.admin_level as int)) END) as admin_level,
  way from planet_osm_line l join relation_members rm on l.osm_id=rm.member_id and rm.member_type='W' left join planet_osm_rels r on rm.relation_id=r.id and (r.type='boundary' or r.boundary='administrative') and r.admin_level similar to '^[0-9]+$' where l."boundary" in ('administrative', 'political') and osm_id>0 group by osm_id, l.admin_level, l.way;

create index planet_osm_boundaries_only_way on planet_osm_boundaries_only using gist(way);
