-- Cache functions --
-- cache_insert(id, k, v[, depend])
--   inserts a key/value pair into the cache
--   - id is the string representation of an object (e.g. 'rel_1234'). If this
--     object already exists in the cache, it will be updated
--   - k is the key (a string)
--   - v is a value and will be handled as string
--   - depend is an array of depending objects, e.g. a the geometric
--     representation of a way depends on the way and it's nodes. You don't
--     have to add the id of the object to the depend-array.
--   - returns the value
-- Example: cache_insert('rel_1234', 'foo', 'bar', Array['node_1']);
-- 
-- cache_search(id, k)
--   returns a cached value for this object, if no value exists 'null' is
--   returned
-- Example: cache_search('rel_1234', 'foo') -> 'bar'
--
-- cache_remove(id)
--   deletes all values which belong to this object or depend on it
-- Example: cache_remove('node_1')

drop table if exists osm_cache;
create table osm_cache (
  osm_id		text		not null,
  cache_type		text		not null,
  content		text		null,
  cur_timestamp		timestamp	not null
);
create index osm_cache_id on osm_cache(osm_id);
create index osm_cache_id_type on osm_cache(osm_id, cache_type);

drop table if exists osm_cache_depend;
create table osm_cache_depend (
  osm_id		text		not null,
  cache_type		text		not null,
  depend_id		text		not null
);
create index osm_cache_depend_id on osm_cache_depend(osm_id);
create index osm_cache_depend_id_type on osm_cache_depend(osm_id, cache_type);
create index osm_cache_depend_depid on osm_cache_depend(depend_id);

CREATE OR REPLACE FUNCTION cache_search(text, text) RETURNS text AS $$
DECLARE
  osm_id alias for $1;
  cache_type alias for $2;
  content text;
BEGIN
  content:=(select osm_cache.content from osm_cache where osm_cache.osm_id=osm_id limit 1);
  return content;
END;
$$ LANGUAGE plpgsql stable;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text, text[]) RETURNS text AS $$
DECLARE
  osm_id alias for $1;
  cache_type alias for $2;
  content alias for $3;
  depend alias for $4;
BEGIN
  delete from osm_cache where osm_cache.osm_id=osm_id and osm_cache.cache_type=cache_type;
  delete from osm_cache_depend where osm_cache_depend.osm_id=osm_id and osm_cache_depend.cache_type=cache_type;

  insert into osm_cache values (osm_id, cache_type, content, now());
  insert into osm_cache_depend values (osm_id, cache_type, osm_id);

  if array_lower(depend, 1) is not null then
    for i in array_lower(depend, 1)..array_upper(depend, 1) loop
      insert into osm_cache_depend values (osm_id, cache_type, depend[i]);
    end loop;
  end if;

  return content;
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_insert(text, text, text) RETURNS text AS $$
DECLARE
BEGIN
  return (select cache_insert($1, $2, $3, null::text[]));
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cache_remove(text) RETURNS bool AS $$
DECLARE
  depend_id alias for $1;
BEGIN
  -- raise notice 'cache_remove(%)', depend_id;

  delete from osm_cache
    using osm_cache_depend dep
    where osm_cache.osm_id=dep.osm_id
      and dep.depend_id=depend_id;

  delete from osm_cache_depend
    using osm_cache_depend dep
    where osm_cache_depend.osm_id=dep.osm_id
      and dep.depend_id=depend_id;

  return true;
END;
$$ LANGUAGE plpgsql volatile;
