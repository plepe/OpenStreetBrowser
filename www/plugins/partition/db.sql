create table partition_tables (
  table_name	text	not null,
  parts_id	text[]	not null,
  parts_where	text[]	not null,
  options	hstore	default ''::hstore,
  indexes	text[]	default Array[]::text[],
  primary key(table_name)
);
