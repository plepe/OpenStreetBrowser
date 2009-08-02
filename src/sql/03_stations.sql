-- table point gets a flag, whether it is part of a station-rel
alter table planet_osm_point add column part_of_station int;
update planet_osm_point set part_of_station=1 from relation_members, planet_osm_rels where planet_osm_point.osm_id=relation_members.member_id and relation_members.member_type='1' and planet_osm_rels.id=relation_members.relation_id and planet_osm_rels.type='station' and relation_members.member_role!='nearby';

-- update importance in point where missing or wrong
update planet_osm_point set importance=network where importance is null and network in ('local', 'suburban', 'urban', 'regional', 'national', 'international');
update planet_osm_point set importance='regional' where importance='region';
update planet_osm_point set importance='local' where highway='bus_stop' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_point set importance='local' where railway='tram_stop' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_point set importance='suburban' where railway='halt' and not (importance is null or importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_point set importance='suburban' from planet_osm_line l join planet_osm_ways w on l.osm_id=w.id where planet_osm_point.osm_id=any(w.nodes) and planet_osm_point.railway='station' and l.railway='subway' and (planet_osm_point.importance is null or not planet_osm_point.importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_point set importance='urban' where railway='station' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_point set importance='urban' where amenity='bus_station' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));

create index planet_osm_point_name on planet_osm_point(name);

-- stop_to_station finds nearby stops with same name
drop table if exists planet_osm_stop_to_station;
create table planet_osm_stop_to_station(node_id int4 not null, 
rel_id int4[], stations int4[], name text, importance text, 
primary key(node_id));
insert into planet_osm_stop_to_station select 
  src.osm_id,
  array_unique(to_intarray((select station_rel.id from
      planet_osm_rels station_rel 
    join 
      relation_members station_member on 
      station_member.relation_id=station_rel.id and
      station_member.member_type='1' and
      station_member.member_role!='nearby'
    where
      station_rel.type='station' and
      src.osm_id=station_member.member_id
    limit 1))),
  array_unique(to_intarray(dst.osm_id)),
  dst.name,
  src.importance
from 
  planet_osm_point dst,
  planet_osm_point src
where 
  src.name=dst.name and
  geometryfromtext('POLYGON((' || xmin(src.way)-500 || ' ' || ymin(src.way)-500 || ','
                               || xmax(src.way)+500 || ' ' || ymin(src.way)-500 || ','
			       || xmax(src.way)+500 || ' ' || ymax(src.way)+500 || ','
			       || xmin(src.way)-500 || ' ' || ymax(src.way)+500 || ',' 
			       || xmin(src.way)-500 || ' ' || ymin(src.way)-500 || '))',
			        900913)&&dst.way and
  Distance(src.way, dst.way)<1000 and
  (src.highway='bus_stop' or src.railway='tram_stop' or
    src.railway='station' or src.railway='halt' or
    src.amenity='bus_station' or src.aeroway='terminal' or
    src.aerialway='station' or src.amenity='ferry_terminal') and
  (dst.highway='bus_stop' or dst.railway='tram_stop' or
    dst.railway='station' or dst.railway='halt' or
    dst.amenity='bus_station' or dst.aeroway='terminal' or
    src.aerialway='station' or dst.amenity='ferry_terminal')
group by src.osm_id, src.importance, dst.name;

-- delete all stops that are part of a station-rel
delete from planet_osm_stop_to_station using planet_osm_point where planet_osm_stop_to_station.node_id=planet_osm_point.osm_id and part_of_station=1;

-- stations_all combines stops that are close to each other
drop table if exists planet_osm_stations;
create table planet_osm_stations(name text, stations int[], rel_id int4, coll_id int4, importance text, lon float, lat float);
SELECT AddGeometryColumn('planet_osm_stations', 'way', 900913, 'MULTIPOINT', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'center', 900913, 'POINT', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'bbox', 900913, 'LINESTRING', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'top', 900913, 'POINT', 2);
insert into planet_osm_stations select name, array_unique(array_sort(stations)), (array_sort(max(rel_id)))[1], (CASE WHEN array_dims(stations)!='[1:1]' THEN (array_sort(stations))[1] ELSE null END),
  (array['local','urban','regional','national','international'])
    [max(CASE WHEN importance='urban' THEN 2 
      WHEN importance='regional' THEN 3
      WHEN importance='national' THEN 4
      WHEN importance='international' THEN 5 ELSE 1 END)],
 avg(lon), avg(lat), ST_Collect(GeomFromText('POINT(' || lon || ' ' || lat || ')', 900913)) from planet_osm_stop_to_station station join planet_osm_nodes node on node.id=any(station.stations) group by name, array_sort(stations), array_dims(stations);

-- delete all stations with relations and do it again for them
insert into planet_osm_stations select name, to_intarray(planet_osm_nodes.id), planet_osm_rels.id, null, importance, avg(lon), avg(lat), ST_Collect(GeomFromText('POINT(' || lon || ' ' || lat || ')', 900913))  from planet_osm_rels join relation_members on relation_members.relation_id=planet_osm_rels.id and member_type='1' join planet_osm_nodes on planet_osm_nodes.id=relation_members.member_id where type='station' group by planet_osm_rels.id, name, importance;
update planet_osm_stations set 
  center=ST_Centroid(way),
  bbox=geomfromtext('LINESTRING(' || XMIN(way)||' '||YMIN(way) ||', '|| XMIN(way)||' '||YMAX(way) ||', '|| XMAX(way)||' '||YMAX(way)||', '|| XMAX(way)||' '||YMIN(way)||', '|| XMIN(way)||' '||YMIN(way) ||', '|| XMIN(way)||' '||YMAX(way)|| ')', 900913), 
  top=GeometryFromText('POINT(' || x(centroid(envelope(way))) || ' ' || ymax(envelope(way)) || ')', 900913);

-- feed stations in collection
insert into planet_osm_colls select (array_sort(stations))[1], 'station' from planet_osm_stations where rel_id is null and array_dims(stations)!='[1:1]';
insert into coll_tags select (array_sort(stations))[1], 'name', name from planet_osm_stations where rel_id is null and array_dims(stations)!='[1:1]';
insert into coll_tags select (array_sort(stations))[1], 'importance', importance from planet_osm_stations where rel_id is null and array_dims(stations)!='[1:1]';
insert into coll_tags select (array_sort(stations))[1], 'type', 'station' from planet_osm_stations where rel_id is null and array_dims(stations)!='[1:1]';
insert into coll_members select (array_sort(stations))[1], p.osm_id, 1, '' from planet_osm_stations st left join planet_osm_point p on p.osm_id=any(st.stations) where rel_id is null and array_dims(stations)!='[1:1]';

-- feed stations in search
insert into search select name, name, null as language, (CASE WHEN rel_id is not null THEN 'rel' WHEN array_dims(stations)!='[1:1]' THEN 'coll' ELSE 'node' END), (CASE WHEN rel_id is not null THEN rel_id ELSE (array_sort(stations))[1] END) as id, 'station', null  from planet_osm_stations where name is not null;

create index planet_osm_stations_way on planet_osm_stations using gist(way);
create index planet_osm_stations_bbox on planet_osm_stations using gist(bbox);
create index planet_osm_stations_center on planet_osm_stations using gist(center);
create index planet_osm_stations_top on planet_osm_stations using gist(top);
