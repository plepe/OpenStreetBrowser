create table customCategory (
  id            char(32)        not null,
  content       mediumtext      not null,
  created       datetime        not null default CURRENT_TIMESTAMP,
  primary key(id)
);

create table customCategoryAccess (
  id            char(32)        not null,
  ts            datetime        not null default CURRENT_TIMESTAMP,
  foreign key(id) references customCategory(id) on delete cascade
);
