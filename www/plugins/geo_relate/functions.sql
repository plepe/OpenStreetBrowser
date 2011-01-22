-- collects all objects with common tags in a specified distance
-- returns object with lowest id
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

  for ret in select * from osm_all where osm_way && ST_Buffer(way, dist) and Distance(osm_way, way)<200 and osm_tags @> match and not osm_id=any(done) loop
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
raise notice 'test %', id;

  ret:=cache_search(id, 'geo_relate_collect|'||cast(match as text)||'|'||dist);
  if ret is not null then
    raise notice 'cache hit % %', id, ret;
    return ret;
  end if;
  
  -- raise notice 'searching for stops: % %', id, tags->parse;

  list:=geo_relate_collect(id, tags, way, match, dist, Array[]::text[]);
  ret:=(array_sort(list))[1];

  -- we can remember the lowest id for all objects in the group
  for i in array_lower(list, 1)..array_upper(list, 1) loop
    raise notice 'insert %', list[i];
    perform cache_insert(list[i], 'geo_relate_collect|'||cast(match as text)||'|'||dist, ret, list);
  end loop;

  return ret;
END;
$$ LANGUAGE plpgsql immutable;
