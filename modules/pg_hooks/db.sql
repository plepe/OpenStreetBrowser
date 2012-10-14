-- **
-- * Holds a list of all functions which registered a hook
-- * @row text hook to which hook the function was registered
-- * @row text fun the name of the function
-- * @row cparams int the count of params the function should be called with (max. 4)
drop table if exists hooks;
create table hooks (
  hook		text		not null,
  fun		text		not null,
  cparams	int		not null default 0,
  primary key(hook, fun)
);
create index hooks_hook on hooks(hook);


