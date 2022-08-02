create table customCategory (
  id            char(32)        not null,
  content       mediumtext      not null,
  created       datetime        not null default CURRENT_TIMESTAMP,
  lastAccess    datetime        not null default CURRENT_TIMESTAMP,
  primary key(id)
);
