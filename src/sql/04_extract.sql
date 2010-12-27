-- osm_polygon_extract
create view osm_polygon_extract as
  select * from osm_polygon where ST_Area(osm_way)>1000000;
create index osm_polygon_extract_idx on osm_polygon using gist(osm_way, osm_tags) where
  ST_Area(osm_way)>1000000;

-- osm_line_extract
drop view osm_line_extract;
create view osm_line_extract as
  select * from osm_line where
    osm_tags->'highway' in ('motorway', 'trunk', 'primary', 'secondary', 'tertiary') or
    osm_tags->'railway' in ('rail') or
    osm_tags->'power' in ('line') or
    osm_tags->'man_made' in ('pipeline') or
    osm_tags->'aeroway' in ('runway') or
    (osm_tags->'waterway' in ('river', 'canal') and coalesce(parse_number(osm_tags->'width'), parse_number(osm_tags->'est_width'))>10);
drop index osm_line_extract_idx;
create index osm_line_extract_idx on osm_line using gist(osm_way, osm_tags) where
    osm_tags->'highway' in ('motorway', 'trunk', 'primary', 'secondary', 'tertiary') or
    osm_tags->'railway' in ('rail') or
    osm_tags->'power' in ('line') or
    osm_tags->'man_made' in ('pipeline') or
    osm_tags->'aeroway' in ('runway') or
    (osm_tags->'waterway' in ('river', 'canal') and coalesce(parse_number(osm_tags->'width'), parse_number(osm_tags->'est_width'))>10);

-- osm_polygon_water
create view osm_polygon_water as
  select * from osm_polygon where
    osm_tags->'natural' IN ('water', 'land', 'bay') OR
    osm_tags->'landuse' IN ('water', 'reservoir', 'lake', 'basin') OR
    osm_tags->'waterway' IN ('dock', 'riverbank');
create index osm_polygon_water_idx on osm_polygon using gist(osm_way, osm_tags) where
    osm_tags->'natural' IN ('water', 'land', 'bay') OR
    osm_tags->'landuse' IN ('water', 'reservoir', 'lake', 'basin') OR
    osm_tags->'waterway' IN ('dock', 'riverbank');

-- osm_point_place_extract
create view osm_point_place_extract as
  select * from osm_point where
    osm_tags->'place' in ('continent', 'country', 'state', 'city', 'county', 'region', 'town', 'island');
create index osm_point_place_extract_idx on osm_point using gist(osm_way, osm_tags) where
    osm_tags->'place' in ('continent', 'country', 'state', 'city', 'county', 'region', 'town', 'island');
