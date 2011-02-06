drop table if exists classify_hmatch;
create table classify_hmatch (
  type		text		not null,
  match		hstore		not null,
  key_exists	text		null,
  result	hstore		null,
  importance	int		not null default 0
);

create index classify_hmatch_idx_notkeyexists on classify_hmatch using gist("match", "type") where key_exists is not null;
create index classify_hmatch_idx_keyexists on classify_hmatch using gist("match", "type") where key_exists is null;
