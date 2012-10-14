CREATE OR REPLACE FUNCTION geo_analyze(geometry, text default '') RETURNS text AS $$
DECLARE
  geo alias for $1;
  prefix alias for $2;
  ret text;
  i int;
BEGIN
  ret:=prefix || GeometryType(geo);
  if ST_NumGeometries(geo) is not null then
    ret:=ret || E' (' || ST_NumGeometries(geo) || E' geoms)';

    for i in 1..ST_NumGeometries(geo) loop
      ret:=ret || E'\n' || geo_analyze(GeometryN(geo, i), prefix || i||': ');
    end loop;
  else
    if GeometryType(geo)='POINT' then
      ret:=ret || ', ' || astext(geo);
    elsif GeometryType(geo)='LINESTRING' then
      ret:=ret || ', ' || astext(ST_StartPoint(geo)) || '..' || astext(ST_EndPoint(geo)) ||
        ', Length: ' || ST_Length(geo);
    end if;
  end if;

  return ret;
END;
$$ LANGUAGE plpgsql immutable;


