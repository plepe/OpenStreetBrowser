create or replace function highway_type(hstore)
returns text language sql
as $$
select (CASE
  WHEN $1->'highway' in ('unclassified', 'road', 'residential') THEN 'minor'
  WHEN $1->'highway' in ('living_street', 'pedestrian', 'byway') THEN 'pedestrian'
  WHEN $1->'highway' in ('service', 'bus_guideway') THEN 'service'
  WHEN $1->'highway' in ('primary', 'secondary', 'tertiary') THEN $1->'highway'
  WHEN $1->'highway' in ('path', 'cycleway', 'footway', 'bridleway', 'steps') THEN 'path'
  WHEN $1->'railway' in ('tram', 'light_rail', 'narrow_gauge') THEN 'tram'
  WHEN $1->'railway' in ('rail', 'subway', 'preserved', 'monorail') THEN 'rail'
  ELSE $1->'highway' END);
$$;

create or replace function importance_order(text)
returns int language sql
as $$
select (CASE
  WHEN $1='local' THEN 1
  WHEN $1='suburban' THEN 2
  WHEN $1='urban' THEN 3
  WHEN $1='regional' THEN 4
  WHEN $1='national' THEN 5
  WHEN $1='international' THEN 6
  WHEN $1='global' THEN 7
  ELSE 0 END);
$$;

create or replace function importance_text(int)
returns text language sql
as $$
select (Array['local', 'suburban', 'urban', 'regional', 'national', 'international', 'global'])[$1];
$$;
