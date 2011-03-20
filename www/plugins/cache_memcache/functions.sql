CREATE OR REPLACE FUNCTION cache_search(text, text) RETURNS text AS $$
DECLARE
  osm_id 	alias for $1;
  cache_type 	alias for $2;
  content 	text;
  key           text;
BEGIN
  key:=md5(osm_id || '|' || cache_type);
  content:=memcache_get(key);

  if content='NULL' then
    return null;
  end if;

  return content;
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text, text[], interval) RETURNS text AS $$
DECLARE
  osm_id   	alias for $1;
  cache_type 	alias for $2;
  content	alias for $3;
  depend	alias for $4;
  outdate	alias for $5;
  _outdate	interval:='2 days';
  ret 		text;
  key           text;
BEGIN
  if outdate is not null then
    _outdate:=outdate;
  end if;

  ret:=content;
  if ret is null then
    ret:='NULL';
  end if;

  key:=md5(osm_id || '|' || cache_type);
  -- raise notice 'insert: (% / %, %, %)', osm_id || '|' || cache_type, key, ret, _outdate;
  perform memcache_set(key, ret, _outdate);

  perform cache_set_depend(key, osm_id, _outdate);

  if array_lower(depend, 1) is not null then
    for i in array_lower(depend, 1)..array_upper(depend, 1) loop
      perform cache_set_depend(key, depend[i], _outdate);
    end loop;
  end if;

  return content;
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text) RETURNS text AS $$
DECLARE
BEGIN
  return (select cache_insert($1, $2, $3, null::text[], null::interval));
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text, text[]) RETURNS text AS $$
DECLARE
BEGIN
  return (select cache_insert($1, $2, $3, $4, null::interval));
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text, interval) RETURNS text AS $$
DECLARE
BEGIN
  return (select cache_insert($1, $2, $3, null::text[], $4));
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text, text) RETURNS text AS $$
DECLARE
BEGIN
  return (select cache_insert($1, $2, $3, null::text[], cast($4 as interval)));
END;
$$ LANGUAGE plpgsql volatile;


CREATE OR REPLACE FUNCTION cache_set_depend(text, text, text, interval) RETURNS void AS $$
DECLARE
BEGIN
  perform cache_set_depend(md5($1 || $2), $3, $4);
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_set_depend(text, text, interval) RETURNS void AS $$
DECLARE
  key           alias for $1;
  depend	alias for $2;
  outdate	alias for $3;
  cur_depend    text[];
  new_depend    text[];
  cur           text[];
  dep_outdate   timestamptz;
BEGIN
  new_depend:=Array[now()];
  cur_depend:=cast(memcache_get('depend|' || depend) as text[]);

  if cur_depend is null then
    cur_depend:=Array[now()];
    dep_outdate:=now();
  else
    new_depend:=Array[cur_depend[1]];
    dep_outdate:=cur_depend[1];
  end if;

  if dep_outdate<now()+outdate then
    dep_outdate:=now()+outdate;
    new_depend[1]=dep_outdate;
  end if;

  for i in 2..array_upper(cur_depend, 1) loop
    cur:=cast(cur_depend[i] as text[]);
    if cur[2]=key then
    elsif cast(cur[1] as timestamptz)>now() then
      new_depend:=array_append(new_depend, cast(cur as text));
    end if;
  end loop;

  new_depend:=array_append(new_depend, cast(Array[
    cast(now()+outdate as text),
    key
  ] as text));

  -- raise notice 'new depend: %', new_depend;

  perform memcache_set('depend|' || depend, cast(new_depend as text));
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_remove(text) RETURNS bool AS $$
DECLARE
  osm_id   	alias for $1;
  cur_depend    text[];
  cur           text[];
BEGIN
  if osm_id is null then
    return;
  end if;

  cur_depend:=cast(memcache_get('depend|' || osm_id) as text[]);
  -- raise notice 'remove % (cur depend: %)', osm_id, cur_depend;

  if cur_depend is null then
    return;
  end if;

  for i in 2..array_upper(cur_depend, 1) loop
    cur:=cast(cur_depend[i] as text[]);

    if cast(cur[1] as timestamptz)>now() then
      perform memcache_delete(cur[2]);
    end if;
  end loop;

  perform memcache_delete('depend|' || osm_id);
END;
$$ LANGUAGE plpgsql volatile;
