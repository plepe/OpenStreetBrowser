create table osb.talk (
  page		text		not null,
  version	varchar(32)	not null,
  tags		hstore		default ''::hstore,
  content	text		default '',
  version_tags	hstore		default ''::hstore,
  parent	varchar(32)	null,
  primary key(version)
);

create table osb.talk_current (
  page		text		not null,
  version	varchar(32)	not null,
  now		timestamp with time zone	not null,
  primary key(page)
);
