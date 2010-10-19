-- **
-- * Hooks - Functions can register to hooks and will be called on certain 
-- * events in the system. They are similar to hooks in PHP/JS
-- *
-- * <code>
-- * CREATE OR REPLACE FUNCTION example(text) RETURNS text AS $$
-- * DECLARE
-- * BEGIN
-- *   raise notice 'example %', $1;
-- *   return $1;
-- * END;
-- * $$ LANGUAGE plpgsql;
-- * 
-- * select register_hook('test_hook', 'example', 1);
-- * </code>
-- *

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

-- **
-- * Register a function to a hook
---*
-- * The function wil always be called with the params the call_hooks function 
-- * has been called with (except cparams is 0). The function should return 
-- * the value of the first param, but is allowed to modify it for the next 
-- * called hook resp. the return value of the function. (In comparision to 
-- * JS/PHP: There the first value is passed by reference and can directly be 
-- * modified)
-- *
-- * @param text hook The hook the function to register to
-- * @param text fun The name of the function
-- * @param int cparams The count of params the function should be called with
CREATE OR REPLACE FUNCTION register_hook(text, text, int) RETURNS void AS $$
DECLARE
  hook alias for $1;
  fun  alias for $2;
  cparams alias for $3;
BEGIN
  delete from hooks where hooks.hook=hook and hooks.fun=fun;
  insert into hooks values (hook, fun, cparams);
END;
$$ LANGUAGE plpgsql volatile;

-- **
-- * Call hooks - All registered functions will be called
-- * @param text hooks The hooks to be called
-- * @param text var All passed variables will be passed to the hooks (up to 
-- * the registered amount of params). The return value of each hook will be 
-- * passed as var1 to the next (see documentation of register_hook)
CREATE OR REPLACE FUNCTION call_hooks(text, text default null, text default null, text default null, text default null) RETURNS text AS $$
DECLARE
  hook		alias for $1;
  var		text;
  entry		record;
  ret		text;
  i		int;
  params	text[];
BEGIN
  var:=$2;

  for entry in select * from hooks where hooks.hook=hook loop

    params:=Array[]::text[];
    for i in 1..entry.cparams loop
      params:=array_append(params, '$'||i);
    end loop;

    execute 'select '||entry.fun||'('||array_to_string(params, ', ')||')' into ret using var, $3, $4, $5;

    if entry.cparams>0 then
      var:=ret;
    end if;
  end loop;
  return ret;
END;
$$ LANGUAGE plpgsql volatile;
