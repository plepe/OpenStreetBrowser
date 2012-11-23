drop table if exists @userdata@.talk;
create table @userdata@.talk (
  page		text		not null,
  version	varchar(32)	not null,
  tags		hstore		default ''::hstore,
  content	text		default '',
  version_tags	hstore		default ''::hstore,
  parent	varchar(32)	null,
  primary key(version)
);

drop table if exists @userdata@.talk_current;
create table @userdata@.talk_current (
  page		text		not null,
  version	varchar(32)	not null,
  now		timestamp with time zone	not null,
  primary key(page)
);
