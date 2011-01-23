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

  for ret in select * from osm_point where osm_way && ST_Buffer(way, 200) and osm_tags @> (parse => (tags->parse))::hstore and not osm_id=any(done) loop
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
  if not tags?parse then
    return id;
  end if;

  ret:=cache_search(id, 'point_group|'||parse);
  if ret is not null then
    return ret;
  end if;
  
  -- raise notice 'searching for stops: % %', id, tags->parse;

  list:=stop_merge(id, tags, way, parse, Array[]::text[]);
  ret:=(array_sort(list))[1];

  -- we can remember the lowest id for all objects in the group
  for i in array_lower(list, 1)..array_upper(list, 1) loop
    perform cache_insert(list[i], 'point_group|'||parse, ret, list);
  end loop;

  return ret;
END;
$$ LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION stops_importance(hstore[]) RETURNS text AS $$
DECLARE
  tags alias for $1;
BEGIN
  return 'urban';
END;
$$ LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION stops_dir(text, hstore, geometry) RETURNS text AS $$
DECLARE
  id alias for $1;
  tags alias for $2;
  way alias for $3;
  result record;
  ret text;
  dir int:=0;
  member_of text[]:=Array[]::text[];
BEGIN
  -- if direction is defined in the stop, return without checking routes
  if tags->'direction' in ('forward', 'backward', 'both') then
    return tags->'direction';
  end if;

  -- maybe we checked direction before?
  ret:=cache_search(id, 'stops_dir');
  if ret is not null then
    return ret;
  end if;

  -- go through all relations this stop is member of and check role of stop
  for result in
    select
      r.osm_id as "osm_id",
      r.member_roles[array_pos(r.member_ids, id)] as "role"
    from osm_rel r
    where
      r.member_ids @> Array[id] and
      r.osm_tags @> 'type=>route' and
      r.osm_tags->'route' in ('train', 'rail', 'railway', 'subway', 'ferry', 'tram', 'trolley', 'trolleybus', 'bus', 'minibus', 'tram', 'light_rail')
  loop
    -- remember list of routes we are member of
    member_of:=array_append(member_of, result.osm_id);

    -- check role: forward*, backward*, else
    if substr(result.role, 1, 7)='forward' then
      dir:=dir|1;
    elsif substr(result.role, 1, 8)='backward' then
      dir:=dir|2;
    else
      dir:=dir|3;
    end if;
  end loop;

  -- direction is one of:
  ret:=(Array['both', 'forward', 'backward', 'both'])[dir+1];

  -- write to cache, maybe we need this again
  perform cache_insert(id, 'stops_dir', ret, member_of);

  return ret;
END;
$$ LANGUAGE plpgsql immutable;

