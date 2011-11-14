CREATE OR REPLACE FUNCTION geo_modify_buffer(in geo_object, in param hstore, in context hstore, out ob geo_object, out geo geometry) AS $$
DECLARE
  param alias for $2;
  context alias for $3;
BEGIN
  ob:=$1;

  geo:=ST_Buffer(ob.way, parse_number(param->'radius'));
  ob.way:=geo;
END;
$$ language 'plpgsql';

-- thanks to http://proceedings.esri.com/library/userconf/proc01/professional/papers/pap388/p388.htm (chapter 3)
CREATE OR REPLACE FUNCTION geo_modify_get_center(in geo_object, in param hstore, in context hstore, out ob geo_object, out geo geometry) AS $$
DECLARE
  param alias for $2;
  context alias for $3;
  radius float:=0;
  i int:=0;
  max_i int;
  max_size float:=0;
  test geometry;
BEGIN
  ob:=$1;
  geo:=ob.way;

  -- start with buffer radius sqrt(area)/2 - can't be bigger than that
  radius=sqrt(ST_Area(geo))/2;

  -- try to reduce buffer radius until we are successful
  loop
    -- calculate geometry with negative buffer
    geo:=ST_Buffer(ob.way, -radius);
    --ob.tags=ob.tags || (('#geo_modify:radius'||i)=>cast(radius as text));
    --ob.tags=ob.tags || (('#geo_modify:it'||i)=>cast(ST_Area(geo) as text));
    --ob.tags=ob.tags || (('#geo_modify:num'||i)=>cast(ST_NumGeometries(geo) as text));

    -- we were successful -> exit loop
    if ST_NumGeometries(geo)>0 or ST_NumGeometries(geo) is null then
      exit;
    end if;

    -- still nothing? -> exit too
    if i>15 then
      exit;
    end if;

    -- geometry is empty -> reduce radius and try again
    radius:=radius/1.5;
    i:=i+1;
  end loop;

  -- nothing found ... return Centroid of polygon
  if ST_NumGeometries(geo)=0 then
    geo:=ST_Centroid(ob.way);
    ob.way:=geo;
    return;
  end if;

  test:=geo;

  -- do it once again, with smaller change to improve likeliness to find a point close to the centroid
  radius:=radius/1.2;
  i:=i+1;
  geo:=ST_Buffer(ob.way, -radius);
  test:=ST_Collect(test, geo);
  --ob.tags=ob.tags || (('#geo_modify:radius'||i)=>cast(radius as text));
  --ob.tags=ob.tags || (('#geo_modify:it'||i)=>cast(ST_Area(geo) as text));
  --ob.tags=ob.tags || (('#geo_modify:num'||i)=>cast(ST_NumGeometries(geo) as text));

  -- now find the point on the created geometry which is closest to the centroid -> that's our new center
  geo:=ST_ClosestPoint(geo, ST_Centroid(ob.way));
  test:=ST_Collect(test, geo);

  ob.tags=ob.tags || (('#geo_modify:debug')=>astext(test));

  ob.way=geo;
END;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION geo_modify_grid(in geo_object, in param hstore, in context hstore, out ob geo_object, out geo geometry) AS $$
DECLARE
  geo geometry;
  param alias for $2;
  grid_size int;
  context alias for $3;
  xmin int; xmax int; ymin int; ymax int;
BEGIN
  ob:=$1;

  xmin:=cast(ceil(XMin(ob.way)) as int);
  xmax:=cast(floor(XMax(ob.way)) as int);
  ymin:=cast(ceil(YMin(ob.way)) as int);
  ymax:=cast(floor(YMax(ob.way)) as int);
  grid_size:=cast(sqrt(ST_Area(ob.way))/10 as int);

  geo:=(select ST_Collect(poi) from
      (select
	ST_SetSRID(ST_Point(x, y), 900913) poi
      from
	generate_series(xmin, xmax, grid_size) x,
	generate_series(ymin, ymax, grid_size) y
      ) points
    where ST_Intersects(poi, ob.way));

  ob.way:=geo;
END;
$$ language 'plpgsql';
