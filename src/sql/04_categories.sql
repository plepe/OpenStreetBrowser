drop table if exists categories_def;
create table categories_def (
  rule_id		text	not null,
  category_id		text	not null,
  rule_tags		hstore,
  primary key(rule_id, category_id)
);
create index categories_def_category_id on categories_def(category_id);
