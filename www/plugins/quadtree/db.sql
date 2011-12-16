create table quadtree_tables (
  table_name	text	not null,
  options	hstore	default ''::hstore,
  primary key(table_name)
);
