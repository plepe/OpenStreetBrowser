update planet_osm_rels set network='urban' where type='route' and route='tram' and (network='' or network is null);
update planet_osm_rels set network='urban' where type='route' and route='bus' and (network='' or network is null);
update planet_osm_rels set network='region' where type='route' and route='light_rail' and (network='' or network is null);
update planet_osm_rels set network='urban' where type='route' and route='trolley' and (network='' or network is null);
update planet_osm_rels set network='region' where type='route' and route='subway' and (network='' or network is null);
update planet_osm_rels set network='region' where type='route' and route='train' and (network='' or network is null);
update planet_osm_rels set network='region' where type='route' and route='rail' and (network='' or network is null);
update planet_osm_rels set network='region' where type='route' and route='railway' and (network='' or network is null);
update planet_osm_rels set network='region' where type='route' and route='ferry' and (network='' or network is null);

alter table planet_osm_line drop column route_level;
alter table planet_osm_line add column route_level int;
update planet_osm_line set "route_level"='1' where "route"='railway';
update planet_osm_line set "route_level"='1' where "route"='rail';
update planet_osm_line set "route_level"='1' where "route"='train';
update planet_osm_line set "route_level"='2' where "route"='subway';
update planet_osm_line set "route_level"='3' where "route"='light_rail';
update planet_osm_line set "route_level"='4' where "route"='tram';
update planet_osm_line set "route_level"='5' where "route"='trolley';
update planet_osm_line set "route_level"='6' where "route"='bus';
update planet_osm_line set "route_level"='7' where "route"='minibus';
update planet_osm_line set "route_level"='1' where "route"='ferry';
drop table planet_osm_line_route;
create table planet_osm_line_route ( osm_id int4 not null, id int4 not null, route text, ref text, network text, routelevel int, z_order int4, primary key(osm_id, id) );
SELECT AddGeometryColumn('planet_osm_line_route', 'way', 900913, 'LINESTRING', 2);
insert into planet_osm_line_route select osm_id, id, planet_osm_rels.route, planet_osm_rels.ref, planet_osm_rels.network, (CASE WHEN planet_osm_rels.route='railway' OR planet_osm_rels.route='rail' OR planet_osm_rels.route='train' OR planet_osm_rels.route='ferry' THEN 1 WHEN planet_osm_rels.route='subway' THEN 2 WHEN planet_osm_rels.route='light_rail' THEN 3 WHEN planet_osm_rels.route='tram' THEN 4 WHEN planet_osm_rels.route='trolley' THEN 5 WHEN planet_osm_rels.route='bus' THEN 6 WHEN planet_osm_rels.route='minibus' THEN 7 END), z_order, way from planet_osm_line, planet_osm_rels where osm_id=ANY(ways_parts) and type='route';

drop table planet_osm_stations;
alter table planet_osm_point add column part_of_station int;
update planet_osm_point set part_of_station=1 from planet_osm_rels where planet_osm_point.osm_id=any(planet_osm_rels.node_parts) and planet_osm_rels.type='station';
update planet_osm_point set network='local' where highway='bus_stop' and (network='' or network is null);
update planet_osm_point set network='local' where railway='tram_stop' and (network ='' or network is null);
update planet_osm_point set network='urban' where railway='halt' and (network='' or network is null);
update planet_osm_point set network='region' where railway='station' and (network='' or network is null);
-- select planet_osm_point.osm_id, planet_osm_point.railway from planet_osm_line join planet_osm_ways on planet_osm_ways.id=planet_osm_line.osm_id join planet_osm_point on planet_osm_point.osm_id=any(planet_osm_ways.nodes) where planet_osm_line.railway='subway';
update planet_osm_point set network='region' where amenity='bus_station' and (network='' or network is null);

grant all on planet_osm_rels to www;
grant all on planet_osm_nodes to www;
grant all on planet_osm_line_route to www;
grant all on planet_osm_ways to www;
grant all on planet_osm_stations to www;

drop table if exists planet_osm_stop_to_station;
create table planet_osm_stop_to_station(node_id int4 not null, 
rel_id int4[], stations int4[], name text, network text, 
primary key(node_id));
-- SELECT AddGeometryColumn('planet_osm_station', 'way', 900913, 'POINT', 2);
drop aggregate if exists to_intarray(int4);
CREATE AGGREGATE to_intarray (
BASETYPE = int4,
SFUNC = array_append,
STYPE = int4[],
INITCOND = '{}'); 
-- collect all stations into planet_osm_station list
insert into planet_osm_stop_to_station select 
  src.osm_id,
  to_intarray(station_rel.rel_id),
  to_intarray(dst.osm_id),
  dst.name,
  src.network
--  ,pos.lon, pos.lat
--  GeomFromText('POINT(' || avg(lon) || ' ' || avg(lat) || ')', 900913)
from 
  planet_osm_point dst,
  planet_osm_point src left join
  planet_osm_rels_members station_rel on 
    station_rel.rel_type='station' and
    src.osm_id=station_rel.mem_id and
    station_rel.type='node' and
    station_rel.role!='nearby'
--  ,planet_osm_nodes pos
where 
--  src.osm_id=pos.id and
  src.name=dst.name and
  Distance(src.way, dst.way)<100 and
  (src.highway='bus_stop' or src.railway='tram_stop' or
    src.railway='station' or src.railway='halt' or
    src.amenity='bus_station' or src.aeroway='station' or
    src.amenity='ferry_terminal') and
  (dst.highway='bus_stop' or dst.railway='tram_stop' or
    dst.railway='station' or dst.railway='halt' or
    dst.amenity='bus_station' or dst.aeroway='station' or
    dst.amenity='ferry_terminal')
group by src.osm_id, station_rel.rel_id, src.network, dst.name;

CREATE OR REPLACE FUNCTION array_sort (ANYARRAY)
RETURNS ANYARRAY LANGUAGE SQL
AS $$
SELECT ARRAY(
  SELECT $1[s.i] AS "foo"
  FROM generate_series(array_lower($1,1), array_upper($1,1)) AS s(i)
  ORDER BY foo
);
$$;
drop table if exists planet_osm_stations_all;
create table planet_osm_stations_all(name text, stations int[], rel_id int4, network text, lon float, lat float);
SELECT AddGeometryColumn('planet_osm_stations_all', 'way', 900913, 'POINT', 2);
insert into planet_osm_stations_all select name, array_sort(stations), max(rel_id),
  (array['local','urban','region','national','international'])
    [max(CASE WHEN network='urban' THEN 2 
      WHEN network='region' THEN 3
      WHEN network='national' THEN 4
      WHEN network='international' THEN 5 ELSE 1 END)],
 avg(lon), avg(lat), GeomFromText('POINT(' || avg(lon) || ' ' || avg(lat) || ')', 900913) from planet_osm_station station join planet_osm_nodes node on node.id=any(station.stations) group by name, array_sort(stations);


-- delete all stations with relations and do it again for them
drop table if exists planet_osm_stations_rel;
create table planet_osm_stations_rel(name text, stations int[], rel_id int4 not null, network text, lon float, lat float, primary key(rel_id));
SELECT AddGeometryColumn('planet_osm_stations_rel', 'way', 900913, 'POINT', 2);
insert into planet_osm_stations_rel select name, to_intarray(planet_osm_nodes.id), planet_osm_rels.id, network, avg(lon), avg(lat), GeomFromText('POINT(' || avg(lon) || ' ' || avg(lat) || ')', 900913) from planet_osm_rels join planet_osm_nodes on planet_osm_nodes.id=any(planet_osm_rels.node_parts) where type='station' group by planet_osm_rels.id, name, network;
