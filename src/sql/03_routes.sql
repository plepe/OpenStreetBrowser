-- Update tag network if wrong or missing
update planet_osm_rels set importance=network where importance is null and network in ('international', 'national', 'regional', 'urban', 'suburban', 'local');
update planet_osm_rels set importance='regional' where importance='region';
update planet_osm_rels set importance='suburban' where type='route' and route='tram' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='suburban' where type='route' and route='bus' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='urban' where type='route' and route='light_rail' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='suburban' where type='route' and route='trolley' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='suburban' where type='route' and route='trolleybus' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='urban' where type='route' and route='subway' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='regional' where type='route' and route='train' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='regional' where type='route' and route='rail' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='regional' where type='route' and route='railway' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='regional' where type='route' and route='ferry' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='regional' where type='station' and (importance is null or not importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international'));
update planet_osm_rels set importance='suburban' where network in ('lcn', 'lwn', 'mtb');
update planet_osm_rels set importance='regional' where network in ('rcn', 'rwn');
update planet_osm_rels set importance='national' where network in ('ncn', 'nwn');
update planet_osm_rels set importance='international' where network in ('icn', 'iwn');
update planet_osm_rels set importance='regional' where network='' and route in ('foot', 'hiking');
update planet_osm_rels set importance='suburban' where network='' and route in ('bicycle', 'mtb');
update planet_osm_rels set importance='regional' where importance is null and type='route' and route='hiking';
update planet_osm_rels set importance='urban' where importance is null and type='route' and route in ('foot', 'bicycle', 'mtb');

-- build table with all lines that are part of a route
drop table if exists planet_osm_line_route;
create table planet_osm_line_route (
  osm_id int4 not null, 
  id int4 not null, 
  route text, 
  ref text, 
  role text[],
  layer_level int2,
  importance text, 
  network text, 
  highway text, 
  railway text,
  tunnel text, 
  bridge text,
  oneway text,
  tracks text,
  z_order int4, 
  primary key(osm_id, id) 
);
SELECT AddGeometryColumn('planet_osm_line_route', 'way', 900913, 'LINESTRING', 2);
insert into planet_osm_line_route select osm_id, id, planet_osm_rels.route, planet_osm_rels.ref, to_textarray(relation_members.member_role), planet_osm_line.layer_level, planet_osm_rels.importance, planet_osm_rels.network, planet_osm_line.highway, planet_osm_line.railway, planet_osm_line.tunnel, planet_osm_line.bridge, planet_osm_line.oneway, planet_osm_line.tracks, z_order, way from planet_osm_line join relation_members on planet_osm_line.osm_id=relation_members.member_id and member_type='2' join planet_osm_rels on relation_members.relation_id=planet_osm_rels.id where type='route' group by osm_id, id, planet_osm_rels.route, planet_osm_rels.ref, planet_osm_line.layer_level, planet_osm_rels.importance, planet_osm_rels.network, planet_osm_line.highway, planet_osm_line.railway, planet_osm_line.tunnel, planet_osm_line.bridge, planet_osm_line.oneway, planet_osm_line.tracks, z_order, way;
-- (CASE WHEN planet_osm_rels.route='railway' OR planet_osm_rels.route='rail' OR planet_osm_rels.route='train' OR planet_osm_rels.route='ferry' THEN 1 WHEN planet_osm_rels.route='subway' THEN 2 WHEN planet_osm_rels.route='light_rail' THEN 3 WHEN planet_osm_rels.route='tram' THEN 4 WHEN planet_osm_rels.route='trolley' THEN 5 WHEN planet_osm_rels.route='bus' THEN 6 WHEN planet_osm_rels.route='minibus' THEN 7 END), 

-- find for each line which routes there are
drop table if exists planet_osm_line_routes;
create table planet_osm_line_routes (line_id int4, route_ids int4[], route_refs text[], importance text, route text, tunnel text, bridge text, highway text, railway text); 
SELECT AddGeometryColumn('planet_osm_line_routes', 'way', 900913, 'LINESTRING', 2);

insert into planet_osm_line_routes select line.osm_id as line_id, to_intarray(route.id), to_textarray((CASE WHEN route.ref='' THEN route.name ELSE route.ref END)), 'local', route.route, tunnel, bridge, highway, railway, way from planet_osm_line line join relation_members route_part on line.osm_id=route_part.member_id and route_part.member_type='2' join planet_osm_rels route on route.id=route_part.relation_id where route.type='route' and route.importance in ('local', 'suburban', 'urban', 'regional', 'national', 'international') group by line.osm_id, line.way, route.route, tunnel, bridge, highway, railway;
insert into planet_osm_line_routes select line.osm_id as line_id, to_intarray(route.id), to_textarray((CASE WHEN route.ref='' THEN route.name ELSE route.ref END)), 'suburban', route.route, tunnel, bridge, highway, railway, way from planet_osm_line line join relation_members route_part on line.osm_id=route_part.member_id and route_part.member_type='2' join planet_osm_rels route on route.id=route_part.relation_id where route.type='route' and route.importance in ('suburban', 'urban', 'regional','national','international') group by line.osm_id, line.way, route.route, tunnel, bridge, highway, railway;
insert into planet_osm_line_routes select line.osm_id as line_id, to_intarray(route.id), to_textarray((CASE WHEN route.ref='' THEN route.name ELSE route.ref END)), 'urban', route.route, tunnel, bridge, highway, railway, way from planet_osm_line line join relation_members route_part on line.osm_id=route_part.member_id and route_part.member_type='2' join planet_osm_rels route on route.id=route_part.relation_id where route.type='route' and route.importance in ('urban', 'regional','national','international') group by line.osm_id, line.way, route.route, tunnel, bridge, highway, railway;
insert into planet_osm_line_routes select line.osm_id as line_id, to_intarray(route.id), to_textarray((CASE WHEN route.ref='' THEN route.name ELSE route.ref END)), 'regional', route.route, tunnel, bridge, highway, railway, way from planet_osm_line line join relation_members route_part on line.osm_id=route_part.member_id and route_part.member_type='2' join planet_osm_rels route on route.id=route_part.relation_id where route.type='route' and route.importance in ('regional','national','international') group by line.osm_id, line.way, route.route, tunnel, bridge, highway, railway;
insert into planet_osm_line_routes select line.osm_id as line_id, to_intarray(route.id), to_textarray((CASE WHEN route.ref='' THEN route.name ELSE route.ref END)), 'national', route.route, tunnel, bridge, highway, railway, way from planet_osm_line line join relation_members route_part on line.osm_id=route_part.member_id and route_part.member_type='2' join planet_osm_rels route on route.id=route_part.relation_id where route.type='route' and route.importance in ('national','international') group by line.osm_id, line.way, route.route, tunnel, bridge, highway, railway;
insert into planet_osm_line_routes select line.osm_id as line_id, to_intarray(route.id), to_textarray((CASE WHEN route.ref='' THEN route.name ELSE route.ref END)), 'international', route.route, tunnel, bridge, highway, railway, way from planet_osm_line line join relation_members route_part on line.osm_id=route_part.member_id and route_part.member_type='2' join planet_osm_rels route on route.id=route_part.relation_id where route.type='route' and route.importance in ('international') group by line.osm_id, line.way, route.route, tunnel, bridge, highway, railway;

-- merge lines with same routes to multiline and combine refs of routes after sorting
-- TODO: use natural sort
drop table if exists planet_osm_line_routes_text;
create table planet_osm_line_routes_text (line_ids int4[], route_ids int4[], route_refs text, importance text, route text, tunnel text, bridge text, highway text, railway text);
SELECT AddGeometryColumn('planet_osm_line_routes_text', 'way', 900913, 'MULTILINESTRING', 2);

insert into planet_osm_line_routes_text select to_intarray(line_id), array_sort(route_ids), array_to_string(array_sort(route_refs), ', '), importance, route, tunnel, bridge, highway, railway, ST_Multi(ST_LineMerge(ST_Collect(way))) from planet_osm_line_routes group by array_sort(route_ids), array_sort(route_refs), importance, route, tunnel, bridge, highway, railway;


create index planet_osm_line_route_way on planet_osm_line_route using gist(way);
create index planet_osm_line_route_route on planet_osm_line_route(route);
create index planet_osm_line_routes_text_way on planet_osm_line_routes_text using gist(way);
create index planet_osm_line_routes_text_route on planet_osm_line_routes_text(route);
