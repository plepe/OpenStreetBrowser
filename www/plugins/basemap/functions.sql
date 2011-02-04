CREATE OR REPLACE FUNCTION basemap_rotate_line(text, hstore, geometry, float, float) RETURNS text AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  angle float;
  length alias for $5;
  ret geometry;
BEGIN
  angle:=$4;
  if angle is null then
    angle:=pi()/2;
  end if;

  ret:=ST_GeomFromText('LINESTRING(0 -'||(length)||',0 '||(length)||')');
  ret:=ST_Rotate(ret, -angle);
  ret:=ST_Translate(ret, X(way), Y(way));

  return ret;
END;
$$ LANGUAGE plpgsql immutable;
