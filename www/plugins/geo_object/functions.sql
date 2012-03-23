-- usage:
---- convert osm_line object to (row type) geo_object:
-- cast(row(osm_id, osm_tags, osm_way) as geo_object)
-- -> you can use this as parameter for a geo_object function
--
---- convert geo_object to columns:
-- geo_object_id(ob) as id, geo_object_tags(ob) as tags, geo_object_way(ob) as way
-- -> you can use this to get the columns returned by a function that returns geo_objects
--
---- Example (both ways):
-- select
--   geo_object_id(ob) as id, geo_object_tags(ob) as tags, geo_object_way(ob) as way
-- from
--   (select
--      a_geo_object_function(cast(row(osm_id, osm_tags, osm_way) as geo_object)) as ob
--    from
--      osm_polygon
--   offset 0);
-- -> use "offset 0", so the 'a_geo_object_function' doesn't get called multiple times

drop type if exists geo_object cascade;
create type geo_object as (
  id	text,
  tags	hstore,
  way	geometry,
  member_ids	text[],
  member_roles	text[]
);

CREATE OR REPLACE FUNCTION geo_object_id(geo_object) RETURNS text AS $$
DECLARE
BEGIN
  return $1.id;
END;
$$ LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION geo_object_tags(geo_object) RETURNS hstore AS $$
DECLARE
BEGIN
  return $1.tags;
END;
$$ LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION geo_object_way(geo_object) RETURNS geometry AS $$
DECLARE
BEGIN
  return $1.way;
END;
$$ LANGUAGE plpgsql immutable;
