CREATE OR REPLACE FUNCTION geom_rotated_line(in geo_object, in param hstore, in context hstore, out ob geo_object, out geo geometry) AS $$
DECLARE
  angle float;
  length float;
BEGIN
  ob:=$1;

  if param ? 'angle' then
    angle:=cast(param->'angle' as float);
  else
    angle:=pi()/2;
  end if;

  if param ? 'length' then
    length:=cast(param->'length' as float);
  else
    length:=200;
  end if;

  geo:=ST_GeomFromText('LINESTRING(0 -'||(length/2)||',0 '||(length/2)||')');
  geo:=ST_Rotate(geo, -angle);
  geo:=ST_Translate(geo, X($1.way), Y($1.way));

  ob.way:=geo;
END;
$$ LANGUAGE plpgsql immutable;
