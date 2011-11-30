CREATE OR REPLACE FUNCTION geom_buffer(in geo_object, in param hstore, in context hstore, out ob geo_object, out geo geometry) AS $$
DECLARE
  param alias for $2;
  context alias for $3;
BEGIN
  ob:=$1;

  geo:=ST_Buffer(ob.way, parse_number(param->'radius'));
  ob.way:=geo;
END;
$$ language 'plpgsql';
