alter table planet_osm_point add column on_highway text;
alter table planet_osm_point add column angle float;
update planet_osm_point set 
on_highway=(select (array['unknown', 'barrier', 'water', 'tram', 'track', 'path', 'service', 'rail', 'pedestrian', 'minor', 'tertiary', 'secondary', 'primary', 'trunk', 'motorway'])[
max(CASE
  WHEN l."power" in ('line', 'minor_line') THEN 35
  WHEN l."highway" in ('motorway') THEN 25
  WHEN l."highway" in ('trunk') THEN 24
  WHEN l."highway" in ('primary') THEN 23
  WHEN l."highway" in ('secondary') THEN 22
  WHEN l."highway" in ('tertiary') THEN 21
  WHEN l."highway" in ('motorway_link') THEN 15
  WHEN l."highway" in ('trunk_link') THEN 14
  WHEN l."highway" in ('primary_link') THEN 13
  WHEN l."highway" in ('unclassified', 'road', 'residential') THEN 10
  WHEN l."highway" in ('living_street', 'pedestrian', 'byway') THEN 9
  WHEN l."railway" in ('rail', 'subway', 'preserved', 'monorail') THEN 8
  WHEN l."highway" in ('service', 'bus_guideway') THEN 7
  WHEN l."highway" in ('path', 'cycleway', 'footway', 'bridleway', 'steps') THEN 6
  WHEN l."highway" in ('track') THEN 5
  WHEN l."railway" in ('tram', 'light_rail', 'narrow_gauge') THEN 4
  WHEN l."man_made" in ('pipeline') THEN 3
  WHEN l."barrier" is not null THEN 3
  WHEN l."natural" in ('cliff') THEN 2
  WHEN l."waterway" in ('river', 'stream', 'canal') THEN 0
ELSE 1 END)] as on_highway
from way_nodes m, planet_osm_line l
where m.node_id=planet_osm_point.osm_id and l.osm_id=m.way_id),

angle=(select avg(angle) from (select avg(CASE WHEN (n.lon-np.lon)=0 THEN 0 ELSE atan((n.lat-np.lat)/(n.lon-np.lon)) END) as angle from way_nodes wn join planet_osm_line l on way_id=l.osm_id left join way_nodes wnp on wn.way_id=wnp.way_id and (wnp.sequence_id=wn.sequence_id-1 or wnp.sequence_id=wn.sequence_id+1) left join planet_osm_nodes n on n.id=wn.node_id left join planet_osm_nodes np on np.id=wnp.node_id where (l.highway is not null or l.railway is not null or l.barrier is not null or l.waterway is not null) and wn.node_id=planet_osm_point.osm_id group by wn.way_id) as angle_t)
where planet_osm_point.highway is not null or planet_osm_point.barrier is not null or planet_osm_point.railway is not null or planet_osm_point.waterway is not null;


-- in ('mini_roundabout', 'level_crossing', 'mountain_pass', 'traffic_signals', 'crossing', 'gate', 'stile', 'cattle_grid', 'toll_booth', 'ford', 'turning_circle')
-- or
-- planet_osm_point.barrier in ('bollard', 'cattle_grid', 'toll_booth', 'entrance', 'gate', 'stile', 'sally_port')
-- or
-- planet_osm_point.waterway in ('dock', 'lock_gate', 'turning_point', 'boatyard', 'weir', 

-- from planet_osm_point p left join  where p.highway='mini_roundabout' group by p.name, l.name, l.highway, l.railway; 


