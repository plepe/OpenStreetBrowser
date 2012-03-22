CREATE OR REPLACE FUNCTION osm(IN bbox geometry, IN _where text default '', IN options hstore default ''::hstore)
RETURNS setof geo_object
AS $$
DECLARE
  sql	text;
BEGIN
  sql='select osm_id as id, osm_tags as tags, osm_way as way, null::text[], null::text[] from osm_point where osm_way && '||quote_nullable(cast(bbox as text));
  if _where!='' then
    sql=sql||' and  '||_where;
  end if;

  return query execute sql;
  return;
END;
$$ language 'plpgsql';

