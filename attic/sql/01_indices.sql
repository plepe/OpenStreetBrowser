drop table if exists indexes;
create table indexes (
  _table		text	not null,
  _key		text	not null,
  _type		text	not null,
  _val		text	null,
  id		text	not null,
  primary key(_table, _key, _type, _val, id)
);
