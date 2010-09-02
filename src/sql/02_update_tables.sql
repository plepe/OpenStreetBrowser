drop table if exists save_actions;
create table save_actions as (select * from actions);

CREATE OR REPLACE FUNCTION osmosisUpdate() RETURNS void AS $$
DECLARE
BEGIN
  raise notice 'called osmosisUpdate()';

  -- for later check make a copy of actions
  delete from save_actions;
  insert into save_actions (select * from actions);

  raise notice 'saved actions';
  
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
  delete from osm_rel using
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R') actions
  where osm_id='rel_'||id;

  raise notice 'deleted from osm_rel';

  -- delete changed/deleted polygons
  delete from osm_polygon using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W'
     union
     select member_id from save_actions actions join relation_members on actions.data_type=relation_members.member_type and actions.id=relation_members.member_id join relation_tags on relation_members.relation_id=relation_tags.relation_id and relation_tags.k='type' and relation_tags.v='multipolygon' where relation_members.member_role='outer' and relation_members.member_type='W') actions
  where osm_id='way_'||id;

  raise notice 'deleted from osm_polygon 1';

  delete from osm_polygon using
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('C', 'M', 'D')) actions
  where osm_id='rel_'||id;

  raise notice 'deleted from osm_polygon 2';

  -- insert changed/created points
  insert into osm_point
    select * from (select
      'node_'||nodes.id as osm_id,
      node_assemble_tags(nodes.id) as osm_tags,
      ST_Transform(geom, 900913) as osm_way
      from nodes
	join actions on nodes.id=actions.id
      where data_type='N' and action in ('C', 'M')
	and abs(Y(geom))!=90
      ) as x
    where (array_dims(akeys(osm_tags)))!='[1:0]';

  raise notice 'inserted to osm_point';

  -- insert changed/created lines
  insert into osm_line
    SELECT
      'way_'||ways.id as osm_id,
	way_assemble_tags(ways.id) as osm_tags,
	ST_SetSRID(way_get_geom(ways.id), 900913) as osm_way
    from ways 
      join (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id union select id from actions where data_type='W' and action in ('C', 'M')) actions 
     on ways.id=actions.id
    group by ways.id;

  raise notice 'inserted to osm_line';

  -- insert changed/created relations
  insert into osm_rel
    select
	'rel_'||relations.id as osm_id,
	rel_assemble_tags(relations.id) as osm_tags,
	ST_Collect((select ST_Transform(geom, 900913) from (
	    select ST_Collect(n.geom) as geom
	      from nodes n inner join relation_members rm on n.id=rm.member_id and rm.member_type='N'
	      where rm.relation_id=relations.id) c),
	  (select ST_Collect(c.geom) from (
	    select ST_SetSRID(way_get_geom(w.id), 900913) as geom
	      from ways w inner join relation_members rm on w.id=rm.member_id and rm.member_type='W'
	      where rm.relation_id=relations.id) c)) as osm_way
      from relations join
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('C', 'M')) actions
  on relations.id=actions.id;

  raise notice 'inserted to osm_rel';

  -- insert changed/created ways
  insert into osm_polygon
    select
      osm_line.osm_id,
      null,
      osm_line.osm_tags,
      MakePolygon(osm_line.osm_way) as osm_way
    from
      osm_line
      join 
       (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
	union
	select id from actions where data_type='W' and action in ('C', 'M')
	) actions 
     on osm_line.osm_id='way_'||actions.id
    where
      IsClosed(osm_line.osm_way) and
      NPoints(osm_line.osm_way)>3 and

      array_upper((
	select
	  to_textarray(osm_rel.osm_id)
	from
	  relation_members
	  join osm_rel on
	    'rel_'||relation_id=osm_rel.osm_id and
	    osm_rel.osm_tags @> 'type=>multipolygon'
	where
	  member_type='W' and
	  member_role in ('outer', '') and
	  member_id=cast((string_to_array(osm_line.osm_id, '_'))[2] as bigint)
      ), 1) is null;

    raise notice 'inserted to osm_polygon 1';

    perform
      assemble_multipolygon(relations.id)
    from
      relations
        join
	  (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
	 union
	 select id from actions where data_type='R' and action in ('C', 'M')
	) actions
        on relations.id=actions.id
	join relation_tags on
	  relation_tags.relation_id=relations.id and
	  relation_tags.k='type'
      where
          relation_tags.v='multipolygon';

  raise notice 'inserted to osm_polygon 2';
  raise notice 'finished osmosisUpdate()';
END;
$$ LANGUAGE plpgsql;
