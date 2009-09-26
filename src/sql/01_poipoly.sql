drop table if exists planet_osm_poipoly;
create table planet_osm_poipoly as select * from planet_osm_point where 1=2;
alter table planet_osm_poipoly drop column way;
SELECT AddGeometryColumn('planet_osm_poipoly', 'way', 900913, 'GEOMETRY', 2);
alter table planet_osm_poipoly add column full_id varchar(32);
alter table planet_osm_poipoly add column id_type varchar(4);

insert into planet_osm_poipoly
  select *, 'node_' || osm_id, 'node' from planet_osm_point;
insert into planet_osm_poipoly
  select *, 'way_' || osm_id, 'way' from planet_osm_polygon;

create index planet_osm_poipoly_id_type     on planet_osm_poipoly("id_type");
create index planet_osm_poipoly_full_id     on planet_osm_poipoly("full_id");
create index planet_osm_poipoly_osm_id      on planet_osm_poipoly("osm_id");
create index planet_osm_poipoly_way         on planet_osm_poipoly using gist(way);

create index planet_osm_poipoly_waterway    on planet_osm_poipoly("waterway");
create index planet_osm_poipoly_railway     on planet_osm_poipoly("railway");
create index planet_osm_poipoly_highway     on planet_osm_poipoly("highway");
create index planet_osm_poipoly_barrier     on planet_osm_poipoly("barrier");
create index planet_osm_poipoly_power       on planet_osm_poipoly("power");
create index planet_osm_poipoly_man_made    on planet_osm_poipoly("man_made");
create index planet_osm_poipoly_addr_street on planet_osm_poipoly("addr:street");
create index planet_osm_poipoly_addr_num    on planet_osm_poipoly("addr:housenumber");
create index planet_osm_poipoly_amenity     on planet_osm_poipoly("amenity");
create index planet_osm_poipoly_shop        on planet_osm_poipoly("shop");
create index planet_osm_poipoly_tourism     on planet_osm_poipoly("tourism");
create index planet_osm_poipoly_historic    on planet_osm_poipoly("historic");
create index planet_osm_poipoly_landuse     on planet_osm_poipoly("landuse");
create index planet_osm_poipoly_cemetery    on planet_osm_poipoly("cemetery");



