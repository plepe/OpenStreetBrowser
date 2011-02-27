CREATE OR REPLACE FUNCTION state_info_update_start() RETURNS boolean AS $$
DECLARE
BEGIN
  update osm_status
    set
      now=now(),
      last_change=(select tstamp from nodes where id=(select max(id) from nodes));

  return true;
END;
$$ language 'plpgsql';

select register_hook('osmosis_update_start', 'state_info_update_start', 0);
