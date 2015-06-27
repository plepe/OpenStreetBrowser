drop table if exists plugins;
create table plugins (
  id		text		not null,
  osm_tags	hstore		null,
  primary key(id)
);

drop table if exists data_request;
create table data_request (
  timestamp     timestamp       not null,
  last_timestamp timestamp      not null,
  category_id   text            not null,
  data          text            not null,
  cache_path    text            not null,
  status        int             not null default 0,
  exit_code     int             null,
  primary key(timestamp)
);
