create table geo (
osm_id	int4	not null,
element text	not null
);
SELECT AddGeometryColumn('geo', 'way', 900913, 'GEOMETRY', 2);

create index geo_pkey on geo(osm_id);
create index geo_element on geo(element);
create index geo_way on geo using gist(way);

insert into geo select osm_id, 'node' as element, way from planet_osm_point;
insert into geo select osm_id, 'way', way from planet_osm_line where osm_id>0;
insert into geo select osm_id, 'way', way from planet_osm_polygon where osm_id>0;
insert into geo select member_id, 'way', way from planet_osm_polygon join relation_members on relation_id=-osm_id and member_type=2 and member_role='outer' where osm_id<0;
insert into geo select -osm_id, 'rel', way from planet_osm_line where osm_id<0;
insert into geo select osm_id, 'coll', way from planet_osm_streets;
