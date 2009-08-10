alter table planet_osm_line add column layer_level int2;
update planet_osm_line 
  set layer_level=(CASE 
    WHEN (layer is null and "power"='line') THEN 5
    WHEN layer in ('-5') THEN -5
    WHEN layer in ('-4') THEN -4
    WHEN layer in ('-3') THEN -3
    WHEN layer in ('-2') THEN -2
    WHEN (layer in ('-1') or (layer is null and tunnel in ('yes', 'true'))) THEN -1
    WHEN ((layer in ('0')) or
	  (layer is null and (tunnel is null or tunnel not in ('yes', 'true')))) THEN 0
    WHEN layer in ('1', '+1') THEN 1
    WHEN layer in ('2', '+2') THEN 2
    WHEN layer in ('3', '+3') THEN 3
    WHEN layer in ('4', '+4') THEN 4
    WHEN layer in ('5', '+5') THEN 5 END)
  where 
    "highway" in ('motorway', 'motorway_link', 'trunk', 'trunk_link', 'primary', 'primary_link', 'secondary', 'tertiary', 'unclassified', 'road', 'residential', 'service', 'bus_guideway', 'track', 'living_street', 'pedestrian', 'byway', 'path', 'cycleway', 'footway', 'bridleway', 'steps') or 
    "railway" is not null or 
    "waterway" in ('river', 'canal', 'stream') or
    "power" in ('line', 'minor_line') or
    "aeroway" in ('runway', 'taxiway') or
    "man_made" in ('pipeline') or
    "natural" in ('cliff') or
    "barrier" in ('wall', 'city_wall', 'fence', 'hedge', 'gate', 'wood_fence', 'wire_fence', 'metal_fence', 'stile', 'retaining_wall', 'footgate', 'unknown', 'horse_jump', 'bollard', 'ditch', 'kissing_gate', 'barrier', 'yes', 'cattle_grid', 'pen_gate', 'step_over', 'v_stile');
create index planet_osm_line_layer_level on planet_osm_line(layer_level);

alter table planet_osm_polygon add column layer_level int2;
update planet_osm_polygon 
  set layer_level=(CASE 
    WHEN layer in ('-5') THEN -5
    WHEN layer in ('-4') THEN -4
    WHEN layer in ('-3') THEN -3
    WHEN layer in ('-2') THEN -2
    WHEN (layer in ('-1') or (layer is null and tunnel in ('yes', 'true'))) THEN -1
    WHEN ((layer in ('0')) or
	  (layer is null and (tunnel is null or tunnel not in ('yes', 'true')))) THEN 0
    WHEN layer in ('1', '+1') THEN 1
    WHEN layer in ('2', '+2') THEN 2
    WHEN layer in ('3', '+3') THEN 3
    WHEN layer in ('4', '+4') THEN 4
    WHEN layer in ('5', '+5') THEN 5 END)
  where 
    "highway" is not null or "power" is not null or
    ("building" is not null or not "building" in ('no'));
create index planet_osm_polygon_layer_level on planet_osm_polygon(layer_level);
