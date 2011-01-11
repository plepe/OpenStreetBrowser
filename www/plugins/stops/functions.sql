-- returns smallest id of matching stop
CREATE OR REPLACE FUNCTION stop_merge(text, hstore, geometry, text, text[]) RETURNS text[] AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  parse alias for $4;
  done text[];
  ret record;
  list_id text[];
  list_tags hstore[];
  list_geo geometry[];
  maybe_min text[];
  i int;
BEGIN
  done:=$5;
  done:=array_append(done, id);

  list_id=Array[]::text[];
  list_tags=Array[]::hstore[];
  list_geo=Array[]::geometry[];

  for ret in select * from osm_point where osm_way && ST_Buffer(way, 200) and osm_tags @> (parse||'=>'||(tags->parse))::hstore and not osm_id=any(done) loop
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
    maybe_min:=array_cat(maybe_min, stop_merge(list_id[i], list_tags[i], list_geo[i], parse, done));
  end loop;

  return maybe_min;
END;
$$ LANGUAGE plpgsql immutable;

-- returns smallest id of matching stop
CREATE OR REPLACE FUNCTION stop_merge(text, hstore, geometry, text) RETURNS text AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  parse alias for $4;
  list text[];
  ret text;
  i int;
BEGIN
  ret:=cache_search(id, 'point_group');
  if ret is not null then
    return ret;
  end if;

  list:=stop_merge(id, tags, way, parse, Array[]::text[]);
  ret:=(array_sort(list))[1];

  -- we can remember the lowest id for all objects in the group
  for i in array_lower(list, 1)..array_upper(list, 1) loop
    perform cache_insert(list[i], 'point_group', ret, list);
  end loop;

  return ret;
END;
$$ LANGUAGE plpgsql immutable;
