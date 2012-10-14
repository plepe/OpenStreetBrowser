drop table if exists classify_hmatch;
create table classify_hmatch (
  type		text		not null,
  match		hstore		not null,
  key_exists	text		null,
  result	hstore		null,
  importance	int		not null default 0,
  type_match	hstore		not null default ''::hstore
);

create index classify_hmatch_idx on classify_hmatch using btree("type");
