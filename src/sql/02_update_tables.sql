--drop table if exists save_actions;
--create table save_actions as (select now(), * from actions);

-- use this to play back save_actions into actions:
-- insert into actions (select data_type, (CASE WHEN oneof_is(to_textarray(action), 'D') THEN 'D' WHEN oneof_is(to_textarray(action), 'C') THEN 'C' ELSE 'M' END), id from save_actions group by data_type, id);

-- **
-- * Hooks:
-- * 'osmosis_update_start' - called when function is called
-- * 'osmosis_update_delete' - called when changed items were removed - use this to remove data from other tables
-- * 'osmosis_update_finish' - called when data has been updated - use this to update data in other tables

CREATE OR REPLACE FUNCTION osmosisUpdate() RETURNS void AS $$
DECLARE
BEGIN
  raise notice 'called osmosisUpdate()';

  perform call_hooks('osmosis_update_start');

  -- for later check make a copy of actions
  -- delete from save_actions;
  insert into save_actions (select now(), * from actions);

  raise notice 'saved actions';

  raise notice E'statistics:\n%', (select array_to_string(to_textarray(stat.text), E'\n') from (select data_type || E'\t' || action || E'\t' || count(id) as text from actions group by data_type, action order by data_type, action) stat);
  
  -- delete cache
  perform cache_remove(
    (CASE WHEN data_type='N' THEN 'node_'||id
          WHEN data_type='W' THEN 'way_'||id
	  WHEN data_type='R' THEN 'rel_'||id
    END)) from actions;

  raise notice 'deleted from osm_cache';

  -- delete changed/deleted points
  delete from osm_point using actions where osm_id='node_'||actions.id and data_type='N';

  raise notice 'deleted from osm_point';

  -- delete changed/deleted lines
  delete from osm_line using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W') actions 
  where osm_id='way_'||id;

  raise notice 'deleted from osm_line';

  -- delete changed/deleted rels
  delete from osm_rel using actions where osm_id='rel_'||actions.id and data_type='R';

  raise notice 'deleted from osm_rel 1';

  delete from osm_rel using
     (select relation_id as id from actions join relation_members on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id) actions
  where osm_id='rel_'||id;

  raise notice 'deleted from osm_rel 2';

  -- delete changed/deleted polygons
  delete from osm_polygon using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W'
     union
     select member_id from actions join relation_members on actions.data_type=relation_members.member_type and actions.id=relation_members.member_id join relation_tags on relation_members.relation_id=relation_tags.relation_id and relation_tags.k='type' and relation_tags.v='multipolygon' where relation_members.member_role='outer' and relation_members.member_type='W') actions
  where osm_id='way_'||id;

  raise notice 'deleted from osm_polygon 1';

  delete from osm_polygon using
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('C', 'M', 'D')) actions
  where osm_id='rel_'||id;

  raise notice 'deleted from osm_polygon 2';

  perform call_hooks('osmosis_update_delete');

  -- insert changed/created points
  perform assemble_point(actions.id) from actions where actions.data_type='N' and actions.action in ('C', 'M');

  raise notice 'inserted to osm_point';

  -- insert changed/created lines
  perform assemble_line(id) from 
    (select id from actions where data_type='W' and action in ('C', 'M')
    union
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id union select id from actions where data_type='W' and action in ('C', 'M'))) actions;

  raise notice 'inserted to osm_line';

  -- insert changed/created relations
  perform assemble_rel(id) from 
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('C', 'M')) actions;

  raise notice 'inserted to osm_rel';

  -- insert changed/created ways
  perform assemble_polygon(id) from
       (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
	union
	select id from actions where data_type='W' and action in ('C', 'M')
	) actions;

    raise notice 'inserted to osm_polygon 1';

    perform
      assemble_multipolygon(actions.id)
    from
	  (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
	 union
	 select id from actions where data_type='R' and action in ('C', 'M')
	) actions
	join relation_tags on
	  relation_tags.relation_id=actions.id and
	  relation_tags.k='type'
      where
          relation_tags.v='multipolygon';

  raise notice 'inserted to osm_polygon 2';

  perform call_hooks('osmosis_update_finish');

  raise notice 'finished osmosisUpdate()';
END;
$$ LANGUAGE plpgsql;
