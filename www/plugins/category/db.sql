create table !schema:osb!.category (
  category_id		text	not null,
  tags			hstore,
  version		text	not null,
  parent_versions	text[]	null,
  version_tags		hstore,
  primary key(category_id, version)
);

create table !schema:osb!.category_rule (
  category_id		text	not null,
  rule_id		text	not null,
  tags			hstore,
  version		text	not null,
  primary key(category_id, version, rule_id)
);

create table !schema:osb!.category_current (
  category_id		text	not null,
  version		text	not null,
  now			timestamp with time zone	not null,
  primary key(category_id)
);
