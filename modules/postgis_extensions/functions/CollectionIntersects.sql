create or replace function CollectionIntersects(geometry, geometry)
  returns bool
  as $$
declare
  geogA    alias for $1;
  geogB    alias for $2;
  tmp      geometry;
  i	   int;
begin
  if GeometryType(geogA)!='GEOMETRYCOLLECTION' and
     GeometryType(geogB)!='GEOMETRYCOLLECTION' then
    return ST_Intersects(geogA, geogB);
  end if;

  if GeometryType(geogA)='GEOMETRYCOLLECTION' then
-- first check all non-collection geometries
    for i in 1..ST_NumGeometries(geogA) loop
      tmp:=ST_GeometryN(geogA, i);
      if GeometryType(tmp)!='GEOMETRYCOLLECTION' then
	if CollectionIntersects(tmp, geogB) then
	  return true;
	end if;
      end if;
    end loop;

-- then check all collection geometries
    for i in 1..ST_NumGeometries(geogA) loop
      tmp:=ST_GeometryN(geogA, i);
      if GeometryType(tmp)='GEOMETRYCOLLECTION' then
	if CollectionIntersects(tmp, geogB) then
	  return true;
	end if;
      end if;
    end loop;

    return false;
  end if;

  if GeometryType(geogB)='GEOMETRYCOLLECTION' then
-- first check all non-collection geometries
    for i in 1..ST_NumGeometries(geogB) loop
      tmp:=ST_GeometryN(geogB, i);
      if GeometryType(tmp)!='GEOMETRYCOLLECTION' then
	if CollectionIntersects(geogA, tmp) then
	  return true;
	end if;
      end if;
    end loop;

-- then check all collection geometries
    for i in 1..ST_NumGeometries(geogB) loop
      tmp:=ST_GeometryN(geogB, i);
      if GeometryType(tmp)='GEOMETRYCOLLECTION' then
	if CollectionIntersects(geogA, tmp) then
	  return true;
	end if;
      end if;
    end loop;

  end if;

  return false;
end;
$$ language 'plpgsql' immutable;
