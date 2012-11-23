drop table if exists @userdata@.category;
create table @userdata@.category (
  category_id		text	not null,
  tags			hstore,
  version		text	not null,
  parent_versions	text[]	null,
  version_tags		hstore,
  primary key(category_id, version)
);

drop table if exists @userdata@.category_rule;
create table @userdata@.category_rule (
  category_id		text	not null,
  rule_id		text	not null,
  tags			hstore,
  version		text	not null,
  primary key(category_id, version, rule_id)
);

drop table if exists @userdata@.category_current;
create table @userdata@.category_current (
  category_id		text	not null,
  version		text	not null,
  now			timestamp with time zone	not null,
  primary key(category_id)
);
