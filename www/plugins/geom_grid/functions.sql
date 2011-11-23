CREATE OR REPLACE FUNCTION geom_grid(in geo_object, in param hstore, in context hstore, out ob geo_object, out geo geometry) AS $$
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
