INSERT into spatial_ref_sys (srid, auth_name, auth_srid, proj4text, srtext) values ( 900913, 'spatialreference.org', 6, '+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs', 'PROJCS[\"unnamed\",GEOGCS[\"unnamed ellipse\",DATUM[\"unknown\",SPHEROID[\"unnamed\",6378137,0]],PRIMEM[\"Greenwich\",0],UNIT[\"degree\",0.0174532925199433]],PROJECTION[\"Mercator_2SP\"],PARAMETER[\"standard_parallel_1\",0],PARAMETER[\"central_meridian\",0],PARAMETER[\"false_easting\",0],PARAMETER[\"false_northing\",0],UNIT[\"Meter\",1],EXTENSION[\"PROJ4\",\"+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs\"]]');

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
