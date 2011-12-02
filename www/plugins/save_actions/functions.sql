CREATE OR REPLACE FUNCTION save_actions_save() RETURNS boolean AS $$
DECLARE
BEGIN
  -- for later check make a copy of actions
  insert into save_actions (select now(), * from actions);

  raise notice 'saved actions';

  return true;
END;
$$ language 'plpgsql';

select register_hook("osmosis_update_finish", "save_actions_save", 0);
