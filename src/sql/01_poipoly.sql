drop table if exists planet_osm_poipoly;
create table planet_osm_poipoly (
  osm_id	int4		not null,
  full_id	varchar(32)	not null,
  id_type	varchar(4)	not null,
  importance	text		,
  network	text		,
  highway	text		,
  railway	text		,
  aeroway	text		,
  aerialway	text		,
  amenity	text		
);
SELECT AddGeometryColumn('planet_osm_poipoly', 'way', 900913, 'GEOMETRY', 2);

insert into planet_osm_poipoly
  select osm_id, 'node_' || osm_id, 'node',
    importance, network, highway, railway, aeroway, aerialway, amenity,
    way
  from planet_osm_point;
insert into planet_osm_poipoly
  select osm_id, 'way_' || osm_id, 'way',
    importance, network, highway, railway, aeroway, aerialway, amenity, 
    way
  from planet_osm_polygon;

create index planet_osm_poipoly_osm_id      on planet_osm_poipoly("osm_id");
create index planet_osm_poipoly_full_id     on planet_osm_poipoly("full_id");
create index planet_osm_poipoly_id_type     on planet_osm_poipoly("id_type");
create index planet_osm_poipoly_way         on planet_osm_poipoly using gist(way);

create index planet_osm_poipoly_railway     on planet_osm_poipoly("railway");
create index planet_osm_poipoly_highway     on planet_osm_poipoly("highway");
create index planet_osm_poipoly_amenity     on planet_osm_poipoly("amenity");
create index planet_osm_poipoly_aeroway	    on planet_osm_poipoly("aeroway");
create index planet_osm_poipoly_aerialway   on planet_osm_poipoly("aerialway");
