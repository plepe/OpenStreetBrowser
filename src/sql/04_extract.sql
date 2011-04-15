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
