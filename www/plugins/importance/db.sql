drop table if exists importance;
create table importance (
  text		text		not null,
  value		int		not null
);

insert into importance values ('global',        65);
insert into importance values ('international', 55);
insert into importance values ('national',      45);
insert into importance values ('regional',      35);
insert into importance values ('urban',         25);
insert into importance values ('suburban',      15);
insert into importance values ('local',          5);

create index importance_text_index on importance(text);
create index importance_value_index on importance(value);
