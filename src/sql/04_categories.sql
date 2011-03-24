drop table if exists category;
create table category (
  category_id		text	not null,
  tags			hstore,
  version		text	not null,
  parent_versions	text[]	null,
  primary key(category_id, version)
);

drop table if exists category_rule;
create table category_rule (
  category_id		text	not null,
  rule_id		text	not null,
  tags			hstore,
  version		text	not null,
  primary key(category_id, version, rule_id)
);

drop table if exists category_current;
create table category_current (
  category_id		text	not null,
  version		text	not null,
  now			timestamp with time zone	not null,
  primary key(category_id)
);
