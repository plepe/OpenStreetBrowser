create table osb.user_list (
  username		text		not null,
  md5_password		text		not null,
  osm_tags		hstore		null,
  primary key(username)
);

drop table if exists auth;
create table auth (
  auth_id		text		not null,
  username		text		not null,
  last_login		timestamp	not null,
  primary key(auth_id)
);
