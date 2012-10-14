drop table if exists osm_cache;
create table osm_cache (
  osm_id		text		not null,
  cache_type		text		not null,
  content		text		null,
  outdate		timestamp	not null
);
create index osm_cache_id on osm_cache(osm_id);
create index osm_cache_id_type on osm_cache(osm_id, cache_type);
create index osm_cache_outdate on osm_cache(outdate);

drop table if exists osm_cache_depend;
create table osm_cache_depend (
  osm_id		text		not null,
  cache_type		text		not null,
  depend_id		text		not null
);
create index osm_cache_depend_id on osm_cache_depend(osm_id);
create index osm_cache_depend_id_type on osm_cache_depend(osm_id, cache_type);
create index osm_cache_depend_depid on osm_cache_depend(depend_id);
