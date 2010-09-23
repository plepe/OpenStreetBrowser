drop aggregate if exists to_array(geometry);
CREATE AGGREGATE to_array (
BASETYPE = geometry,
SFUNC = array_append,
STYPE = geometry[],
INITCOND = '{}'); 

create or replace function make_multipolygon(geometry[])
returns geometry
as $$
declare
  src		alias for $1;
  todo		geometry[];
  done		geometry[];
  cur		geometry;
  cur1		geometry;
begin
  done:=Array[]::geometry[];

  -- empty array
  if src is null or array_lower(src, 1) is null then
    return null;
  end if;

  -- first find all closed geometries in array and push into done
  for i in array_lower(src, 1)..array_upper(src, 1) loop
    if src[i] is null then
      raise notice 'got null geometry, index %', i;
    elsif (IsClosed(src[i])) and NPoints(src[i])>3 then
      done:=array_append(done, ST_MakePolygon(src[i]));
    elsif not ST_IsValid(src[i]) then
      raise notice 'ignore invalid line %', i;
    else
      todo:=array_append(todo, src[i]);
    end if;
  end loop;

  -- merge all other geometries together
  cur:=ST_LineMerge(ST_Collect(todo));

  -- if those build a closed geometry
  if ST_NumGeometries(cur) is null then
    if IsClosed(cur) and NPoints(cur)>3 then
      done:=array_append(done, MakePolygon(cur));
    end if;
  else
  -- several geometries? check each of them ...
    for i in 1..ST_NumGeometries(cur) loop
      cur1:=ST_GeometryN(cur, i);
      if IsClosed(cur1) and NPoints(cur1)>3 then
	done:=array_append(done, ST_MakePolygon(cur1));
      end if;
    end loop;
  end if;

  -- we are done :)
  return ST_Collect(done);
end;
$$ language 'plpgsql';

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
      outer_ways:=ST_Difference(outer_ways, next);
    end if;
  end loop;

  return outer_ways; -- ST_Difference(outer_ways, inner_ways);
end;
$$ language 'plpgsql';
