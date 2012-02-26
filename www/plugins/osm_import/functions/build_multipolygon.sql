create or replace function build_multipolygon(geometry[], geometry[])
returns geometry
as $$
declare
  outer_ways	geometry;
  inner_ways	geometry;
  next		geometry;
  i		int;
begin
  if $1 is null then
    return null;
  end if;

  if coalesce(array_upper($2, 1), 0)>256 then
    raise notice 'multipolygon consists of % (more then 256) inner members ... ignore', coalesce(array_upper($1, 1), 0)+coalesce(array_upper($2, 1), 0);
    return null;
  end if;

  outer_ways:=make_multipolygon($1);

  if outer_ways is null then
    return null;
  end if;

  if not IsValid(outer_ways) then
    return outer_ways;
  end if;

  if $2 is not null then
    inner_ways:=make_multipolygon($2);
  end if;

  if inner_ways is null then
    return outer_ways;
  end if;

  -- raise notice E'outer_ways (%): %\ninner_ways (%): %', NumGeometries(outer_ways), astext(outer_ways), NumGeometries(inner_ways), astext(inner_ways);

  -- substract the inner ways one by one
  for i in 1..ST_NumGeometries(inner_ways) loop
    next:=ST_GeometryN(inner_ways, i);
    if ST_IsValid(next) then
      begin
	outer_ways:=ST_Difference(outer_ways, next);
      exception 
        when others then
	  raise notice 'multipolygon got an error when substracting inner polygons (inner %)', i;
	  return null;
      end;
    end if;
  end loop;

  return outer_ways; -- ST_Difference(outer_ways, inner_ways);
end;
$$ language 'plpgsql';
