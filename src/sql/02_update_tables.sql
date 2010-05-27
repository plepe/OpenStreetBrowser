CREATE OR REPLACE FUNCTION osmosisUpdate() RETURNS void AS $$
DECLARE
BEGIN
  -- delete changed/deleted nodes
  delete from osm_nodes using actions where osm_id=id and data_type='N' and action in ('M', 'D');

  -- insert changed/created nodes
  insert into osm_nodes
    select * from (select
      'node'::text as osm_type,
      nodes.id as osm_id,
      (select
	  array_to_hstore(to_textarray(k), to_textarray(v))
	from node_tags
	where nodes.id=node_id and k!='created_by'
	group by node_id) as osm_tags,
      ST_Transform(geom, 900913) as osm_way
      from nodes
        join actions on nodes.id=actions.id
	where data_type='N' and action in ('M', 'C')
      ) as x
    where (array_dims(akeys(osm_tags)))!='[1:0]';

  -- delete changed/deleted ways
  delete from osm_ways using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W' and action in ('M', 'D'))actions 
  where osm_id=id;

  -- insert changed/created ways
insert into osm_ways
  SELECT
    'way'::text as osm_type,
    ways.id as osm_id,
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from way_tags
      where ways.id=way_id and k!='created_by'
      group by way_id) as osm_tags,
    (SELECT ST_Transform(MakeLine(c.geom), 900913) AS osm_way FROM (
              SELECT n.geom AS geom
              FROM nodes n INNER JOIN way_nodes wn ON n.id = wn.node_id
              WHERE (wn.way_id = ways.id) ORDER BY wn.sequence_id
      ) c) as osm_way
  from ways join
   (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id union select id from actions where data_type='W' and action in ('M', 'D')) actions 
   on ways.id=actions.id
  group by ways.id;

  -- delete changed/deleted rels
  delete from osm_rels using
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('M', 'D')) actions
  where osm_id=id;

  -- insert changed/created relations
  insert into osm_rels
    select
	'rel'::text as osm_type,
	relations.id as osm_id,
	(select
	    array_to_hstore(to_textarray(k), to_textarray(v))
	  from relation_tags
	  where relations.id=relation_id and k!='created_by'
	  group by relation_id) as osm_tags,
	ST_Collect((select ST_Transform(geom, 900913) from (
	    select ST_Collect(n.geom) as geom
	      from nodes n inner join relation_members rm on n.id=rm.member_id and rm.member_type='N'
	      where rm.relation_id=relations.id) c),
	  (select ST_Collect(geom) from (
	    select w.osm_way as geom
	      from osm_ways w inner join relation_members rm on w.osm_id=rm.member_id and rm.member_type='W'
	      where rm.relation_id=relations.id) c)) as osm_way
      from relations join
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('M', 'D')) actions
  on relations.id=actions.id;

END;
$$ LANGUAGE plpgsql;
