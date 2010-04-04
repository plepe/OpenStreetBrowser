drop table if exists categories_def;
create table categories_def (
  category_id		text	not null,
  rule_id		text	not null,
  display_name_pattern	text,
  display_type_pattern	text,
  primary key(category_id, rule_id)
);
