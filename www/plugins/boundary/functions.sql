CREATE OR REPLACE FUNCTION assemble_boundary(bigint) RETURNS boolean AS $$
DECLARE
  id alias for $1;
  geom geometry;
  tags hstore;
  min_admin_level float;
BEGIN
  if id is null then
    return false;
  end if;

  -- get tags
  tags:=tags_merge(way_assemble_tags(id), (select tags_merge(to_array(osm_tags)) as osm_tags from relation_members rm join osm_allrel on 'rel_'||rm.relation_id=osm_allrel.osm_id where id=rm.member_id and rm.member_type='W' group by rm.member_id));

  min_admin_level:=parse_lowest_number(tags->'admin_level');

  if min_admin_level is null then
    return false;
  end if;

  geom:=way_get_geom(id);

  raise notice 'insert id=% level=% full level=(%)', id, min_admin_level, tags->'admin_level';
   
  insert into osm_boundary
    values (
      'way_'||id,
      tags,
      min_admin_level,
      ST_Transform(geom, 900913)
    );
  
  return true;
end;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION boundary_update_delete() RETURNS boolean AS $$
DECLARE
BEGIN
  delete from osm_boundary using
    (select way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' group by way_id
     union
     select id from actions where data_type='W'
     union
     select member_id from relation_members rm join actions on rm.relation_id=actions.id and actions.data_type='R' and actions.action in ('M', 'D') where member_type='W'
     ) actions 
  where osm_id='way_'||id;

  raise notice 'deleted from osm_boundary';

  return true;
END;
$$ language 'plpgsql';


CREATE OR REPLACE FUNCTION boundary_update_insert() RETURNS boolean AS $$
DECLARE
BEGIN
  perform assemble_boundary(id) from
    (select way_id as id from actions join way_tags on actions.id=way_tags.way_id and actions.data_type='W' and actions.action in ('C', 'M') where k='boundary' and v in ('administrative', 'political')
    union
    select relation_members.member_id from (select * from actions where actions.data_type='R' and actions.action in ('C', 'M')) actions join osm_allrel on 'rel_'||actions.id=osm_allrel.osm_id  join relation_members on actions.id=relation_members.relation_id and relation_members.member_type='W' where osm_tags->'boundary'='administrative'
    union
    select way_nodes.way_id as id from way_nodes join actions on way_nodes.node_id=actions.id and actions.data_type='N' and actions.action='M' join way_tags on actions.id=way_tags.way_id and k='boundary' and v in ('administrative', 'political') group by way_nodes.way_id
    ) x;

  raise notice 'inserted to osm_boundary';

  return true;
END;
$$ language 'plpgsql';

-- select register_hook('osmosis_update_delete', 'boundary_update_delete', 0);
-- select register_hook('osmosis_update_insert', 'boundary_update_insert', 0);
