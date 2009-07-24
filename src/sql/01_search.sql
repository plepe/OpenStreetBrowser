-- create table
drop table if exists search;
create table search (
  name		text		,
  loc_name	text		not null,
  language	text		,
  element	text		,
  id		int4		,
  amenity_type	text		,
  amenity_val	text
);
create index search_type on search(amenity_type);
-- create index search_loc_name on search(loc_name);
create index search_idx on search using gin(to_tsvector('english', loc_name));

-- fill places into search
--insert into search select name, name, null as language, (CASE WHEN rel_id is not null THEN 'rel' WHEN array_dims(stations)!='[1:1]' THEN 'coll' ELSE 'node' END), (CASE WHEN rel_id is not null THEN rel_id ELSE (array_sort(stations))[1] END) as id, 'station', null, (CASE WHEN geometrytype(envelope(way)) in ('POINT', 'LINESTRING') THEN geomfromtext('POLYGON((' || xmin(way)-0.1 || ' ' || ymin(way)-0.1 || ',' || xmax(way)+0.1 || ' ' || ymin(way)-0.1 || ',' || xmax(way)+0.1 || ' ' || ymax(way)+0.1 || ',' || xmin(way)-0.1 || ' ' || ymax(way)+0.1 || ',' || xmin(way)-0.1 || ' ' || ymin(way)-0.1 || '))') ELSE envelope(way) END) as area from planet_osm_stations limit 1;
