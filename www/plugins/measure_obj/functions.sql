-- measure_obj_length - return geodesic length of current object
-- @param text osm_id ID of the object
-- @param hstore osm_tags Tags of the object
-- @param geometry osm_way The geometric data of the object
-- @return float Length of the object in meters
CREATE OR REPLACE FUNCTION measure_obj_length(text, hstore, geometry) RETURNS float AS $$
DECLARE
  osm_id   alias for $1;
  osm_tags alias for $2;
  osm_way  alias for $3;
BEGIN
  return ST_Length(ST_Transform(osm_way, 4326)::geography);
END;
$$ LANGUAGE plpgsql;

-- measure_obj_area - return geodesic area of current object
-- @param text osm_id ID of the object
-- @param hstore osm_tags Tags of the object
-- @param geometry osm_way The geometric data of the object
-- @return float Area of the object in square meters
CREATE OR REPLACE FUNCTION measure_obj_area(text, hstore, geometry) RETURNS float AS $$
DECLARE
  osm_id   alias for $1;
  osm_tags alias for $2;
  osm_way  alias for $3;
BEGIN
  return ST_Area(ST_Transform(osm_way, 4326)::geography);
END;
$$ LANGUAGE plpgsql;
