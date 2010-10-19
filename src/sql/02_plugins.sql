drop table if exists plugins;
create table plugins (
  id		text		not null,
  osm_tags	hstore		null,
  primary key(id)
);
