CREATE OR REPLACE FUNCTION extract_update_delete() RETURNS boolean AS $$
DECLARE
  num_rows  int;
BEGIN
  delete from osm_all_extract using
    (select (CASE WHEN data_type='N' THEN 'node_'||id                                             WHEN data_type='W' THEN 'way_'||id                                              WHEN data_type='R' THEN 'rel_'||id                                        END) as id from actions) x
    where osm_id=id;

  GET DIAGNOSTICS num_rows = ROW_COUNT;
  raise notice 'deleted from osm_all_extract (%)', num_rows;

  return true;
END;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION extract_update_insert() RETURNS boolean AS $$
DECLARE
  num_rows  int;
BEGIN
  insert into osm_all_extract (
    select *
    from (
      select
        osm_id,
        classify_hmatch(osm_id, osm_tags, osm_way, Array['extract']) as osm_tags,
        osm_way
      from 
        osm_all join
        actions on
          osm_id=
            (CASE WHEN data_type='N' THEN 'node_'||id                                             WHEN data_type='W' THEN 'way_'||id                                              WHEN data_type='R' THEN 'rel_'||id                                        END) and
          action not in ('D')
       ) x
     where
       osm_tags ? '#extract');

  GET DIAGNOSTICS num_rows = ROW_COUNT;
  raise notice 'inserted to osm_all_extract (%)', num_rows;

  return true;
END;
$$ language 'plpgsql';

select register_hook('osmosis_update_delete', 'extract_update_delete', 0);
select register_hook('osmosis_update_insert', 'extract_update_insert', 0);

