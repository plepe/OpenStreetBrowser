CREATE OR REPLACE FUNCTION geo_modify_buffer(geo_object, hstore, hstore) RETURNS geo_object AS $$
DECLARE
  ob geo_object;
  param alias for $2;
  context alias for $3;
BEGIN
  ob:=$1;

  ob.way=ST_Buffer(ob.way, parse_number(param->'radius'));

  return ob;
END;
$$ language 'plpgsql';
