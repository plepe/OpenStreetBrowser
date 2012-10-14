CREATE OR REPLACE FUNCTION state_info_update_start() RETURNS boolean AS $$
DECLARE
BEGIN
  update osm_status
    set
      now=now(),
      last_change=(select tstamp from nodes where id=(select max(id) from nodes));

  perform cluster_call(
    'update_db',
    cast((select tstamp from nodes where id=(select max(id) from nodes)) as text)
  );

  return true;
END;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION state_info_update_db(text, timestamp with time zone, text) RETURNS void AS $$
DECLARE
BEGIN
  raise notice '% % %', $1, $2, $3;

  perform cache_remove(
    (CASE WHEN data_type='N' THEN 'node_'||id
          WHEN data_type='W' THEN 'way_'||id
	  WHEN data_type='R' THEN 'rel_'||id
    END)) from save_actions where now=$2;
END;
$$ language 'plpgsql';

select register_hook('osmosis_update_start', 'state_info_update_start', 0);
select cluster_call_register('update_db', 'state_info_update_db');
