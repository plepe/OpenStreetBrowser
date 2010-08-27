CREATE OR REPLACE FUNCTION osmosisUpdate() RETURNS void AS $$
DECLARE
BEGIN
  -- delete changed/deleted points
  delete from osm_point using actions where osm_id='node_'||id and data_type='N' and action in ('M', 'D');

  -- insert changed/created points
insert into osm_point
  select * from (select
    'node_'||id as osm_id,
    node_assemble_tags(id) as osm_tags,
    ST_Transform(geom, 900913) as osm_way
    from nodes
      join actions on nodes.id=actions.id
    where data_type='N' and action in ('C', 'M')
      and abs(Y(geom))!=90
    ) as x
  where (array_dims(akeys(osm_tags)))!='[1:0]';

  -- delete changed/deleted lines
  delete from osm_line using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W' and action in ('M', 'D'))actions 
  where osm_id='way_'||id;

  -- insert changed/created lines
insert into osm_line
  SELECT
    'way_'||id as osm_id,
      way_assemble_tags(id) as osm_tags,
      ST_SetSRID(way_get_geom(id), 900913) as osm_way
  from ways 
    join (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id union select id from actions where data_type='W' and action in ('C', 'M')) actions 
   on ways.id=actions.id
  group by ways.id;

  -- delete changed/deleted rels
  delete from osm_rel using
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('M', 'D')) actions
  where osm_id='rel_'||id;

  -- insert changed/created relations
  insert into osm_rel
    select
	'rel_'||relations.id as osm_id,
	rel_assemble_tags(id) as osm_tags,
	ST_Collect((select ST_Transform(geom, 900913) from (
	    select ST_Collect(n.geom) as geom
	      from nodes n inner join relation_members rm on n.id=rm.member_id and rm.member_type='N'
	      where rm.relation_id=relations.id) c),
	  (select ST_Collect(geom) from (
	    select w.osm_way as geom
	      from osm_line w inner join relation_members rm on w.osm_id=rm.member_id and rm.member_type='W'
	      where rm.relation_id=relations.id) c)) as osm_way
      from relations join
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('C', 'M')) actions
  on relations.id=actions.id;

  -- delete changed/deleted polygons
  delete from osm_polygon using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W' and action in ('M', 'D')
     union
     select member_id from actions join osm_rel on osm_rel.osm_id='rel_'||actions.id left join relation_members on cast(substr(osm_rel.osm_id, 5) as bigint)=relation_members.relation_id where osm_rel.osm_tags->'type'='multipolygon' and relation_members.member_role='outer' and action in ('M', 'C', 'D')) actions
  where osm_id='way_'||id;

  delete from osm_polygon using
    (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('M', 'D')) actions
  where osm_id='rel_'||id;

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
   on osm_line.id='way_'||actions.id
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

insert into osm_polygon
  select
    osm_rel.osm_id as osm_id,
    osm_rel.osm_id as rel_id,
    (CASE 
      WHEN ways_outer.count=1 THEN tags_merge(Array[osm_rel.osm_tags, ways_outer.osm_tags])
      ELSE osm_rel.osm_tags
    END) as osm_tags,
    build_multipolygon(ways_outer.osm_way, ways_inner.osm_way) as osm_way
  from
    osm_rel
    join (select relation_id as id from relation_members join actions on relation_members.member_id=actions.id and actions.data_type=relation_members.member_type and actions.action='M' group by relation_id
     union
     select id from actions where data_type='R' and action in ('C', 'M')) actions
  on osm_rel.id='rel_'||actions.id
    left join
    (select
       'rel_'||relation_members.relation_id as rel_id,
       to_textarray(osm_id) as osm_id,
       tags_merge(to_array(osm_tags)) as osm_tags,
       to_array(osm_way) as osm_way,
       count(*) as count
     from
       relation_members
       join osm_line on
         'way_'||member_id=osm_line.osm_id and
	 member_type='W' and member_role in ('outer', '')
     group by relation_members.relation_id
    ) ways_outer on
      osm_rel.osm_id=ways_outer.rel_id
    left join
    (select
       'rel_'||relation_members.relation_id as rel_id,
       to_array(osm_way) as osm_way
     from
       relation_members
       join osm_line on
         'way_'||member_id=osm_line.osm_id and
	 member_type='W' and member_role='inner'
     group by relation_members.relation_id
    ) ways_inner on
      osm_rel.osm_id=ways_inner.rel_id
  where
    osm_rel.osm_tags @> 'type=>multipolygon' and
    ways_outer.osm_id is not null;

END;
$$ LANGUAGE plpgsql;
