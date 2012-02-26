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
  cur:=ST_LineMerge(ST_GeomFromEWKT(ST_AsEWKT(ST_Collect(todo))));

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
