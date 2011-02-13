-- collects all objects with common tags in a specified distance
-- returns smallest id of matching stop
CREATE OR REPLACE FUNCTION geo_relate_collect(text, hstore, geometry, hstore, float) RETURNS text AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  match alias for $4;
  dist alias for $5;
  list text[];
  ret text;
  i int;
BEGIN
  if not tags @> match then
    return null;
  end if;

  ret:=cache_search(id, 'geo_relate_collect|'||cast(match as text)||'|'||dist);
  if ret is not null then
    -- raise notice 'geo_relate:: cache hit % %', id, ret;
    return ret;
  end if;
  
  -- raise notice 'searching for stops: % %', id, tags->parse;

  list:=geo_relate_collect(id, tags, way, match, dist, Array[]::text[]);
  ret:=(array_sort(list))[1];

  -- we can remember the lowest id for all objects in the group
  for i in array_lower(list, 1)..array_upper(list, 1) loop
    -- raise notice 'geo_relate:: insert %', list[i];
    perform cache_insert(list[i], 'geo_relate_collect|'||cast(match as text)||'|'||dist, ret, list);
  end loop;

  return ret;
END;
$$ LANGUAGE plpgsql immutable;

-- main function for geo_relate_collect. returns ids of all objects
CREATE OR REPLACE FUNCTION geo_relate_collect(text, hstore, geometry, hstore, float, text[]) RETURNS text[] AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  match alias for $4;
  dist alias for $5;
  done text[];
  ret record;
  list_id text[];
  list_tags hstore[];
  list_geo geometry[];
  maybe_min text[];
  i int;
BEGIN
  done:=$6;
  done:=array_append(done, id);

  list_id=Array[]::text[];
  list_tags=Array[]::hstore[];
  list_geo=Array[]::geometry[];

  for ret in select * from osm_all where osm_way && ST_Buffer(way, dist) and Distance(osm_way, way)<dist and osm_tags @> match and not osm_id=any(done) loop
    done:=array_append(done, ret.osm_id);
    list_id:=array_append(list_id, ret.osm_id);
    list_tags:=array_append(list_tags, ret.osm_tags);
    list_geo:=array_append(list_geo, ret.osm_way);
  end loop;

  if array_lower(list_id, 1) is null then
    return Array[id];
  end if;

  maybe_min:=Array[id]::text[];
  for i in array_lower(list_id, 1)..array_upper(list_id, 1) loop
    maybe_min:=array_cat(maybe_min, geo_relate_collect(list_id[i], list_tags[i], list_geo[i], match, dist, done));
  end loop;

  return maybe_min;
END;
$$ LANGUAGE plpgsql immutable;

-- @return
--   array of floats:
--   1. angle of the tangent of the nearest point on a line
--   2. angle of the nearest point on a line in backward direction
--   3. angle of the nearest point on a line in forward direction
--   4. angle of the normal to the line between current point and nearest 
--      point on a line (if current point is on the line return angle 1)
CREATE OR REPLACE FUNCTION geo_relate_calc_angles(text, hstore, geometry, float, text default '') RETURNS float[] AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  max_dist alias for $4;
  where_str text:=''; -- alias for $5
  ret record;
  result float[];
  line_point geometry;
  angle float;
  angle_p float;
  angle_n float;
  angle_norm float;
  pos_p float;
  pos_n float;
  length float;
BEGIN
  result:=cache_search(id, 'geo_relate_calc_angles|'||$5||'|'||max_dist);
  if result is not null then
    return cast(result as float[]);
  end if;

  if $5 != '' then
    where_str:='and '||$5;
  end if;

  execute 'select *, line_locate_point(osm_way, $3) as "pos" from osm_line where osm_way && ST_Buffer($3, $4) ' || where_str || ' and Distance($3, osm_way)<$4 order by Distance($3, osm_way) asc limit 1' into ret using id, tags, way, max_dist;

  line_point:=line_interpolate_point(ret.osm_way, ret.pos);

  if ST_Length(ret.osm_way)=0 then
    return null;
  end if;

  length:=ST_Length(ret.osm_way);
  pos_p:=ret.pos-0.001/length;
  pos_n:=ret.pos+0.001/length;

  if pos_p<0 then
    pos_p:=0;
  end if;

  if pos_n>1 then
    pos_n:=1;
  end if;

  -- raise notice 'pos: % % - % - % %', 0, pos_p, ret.pos, pos_n, ST_Length(ret.osm_way);

  angle:=ST_Azimuth(line_interpolate_point(ret.osm_way, pos_p), line_interpolate_point(ret.osm_way, pos_n));

  angle_p:=ST_Azimuth(line_interpolate_point(ret.osm_way, pos_p), line_point);

  angle_n:=ST_Azimuth(line_point, line_interpolate_point(ret.osm_way, pos_n));

  if angle_p is null then
    angle_p:=angle_n;
  elsif angle_n is null then
    angle_n:=angle_p;
  end if;

  angle_norm:=ST_Azimuth(way, line_point)+(pi()/2);
  if angle_norm is null then
    angle_norm:=angle;
  end if;

  -- raise notice 'pos %: angle: % % % %', ret.pos, angle, angle_p, angle_n, angle_norm;

  result:=Array[angle, angle_p, angle_n, angle_norm];

  perform cache_insert(id, 'geo_relate_calc_angles|'||$5||'|'||max_dist, cast(result as text));
  return result;
END;
$$ LANGUAGE plpgsql immutable;

-- calculate nearest point from current object on an object nearby
CREATE OR REPLACE FUNCTION geo_relate_nearest_point(text, hstore, geometry, float, text default '') RETURNS geometry AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  max_dist alias for $4;
  where_str text:=''; -- alias for $5
  ret record;
  geo geometry;
BEGIN
  geo:=cache_search(id, 'geo_relate_nearest_point|'||$5||'|'||max_dist);
  if geo is not null then
    return geo; -- cache hit
  end if;

  if $5 != '' then
    where_str:='and '||$5;
  end if;

  execute 'select *, line_locate_point(osm_way, $3) as "pos" from osm_line where osm_way && ST_Buffer($3, $4) ' || where_str || ' and Distance($3, osm_way)<$4 order by Distance($3, osm_way) asc limit 1' into ret using id, tags, way, max_dist;

  if ret is null then
    return way;
  end if;

  geo:=line_interpolate_point(ret.osm_way, ret.pos);

  perform cache_insert(id, 'geo_relate_nearest_point|'||$5||'|'||max_dist, geo);
  return geo;
END;
$$ LANGUAGE plpgsql immutable;
