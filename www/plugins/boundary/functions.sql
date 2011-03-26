CREATE OR REPLACE FUNCTION assemble_boundary(bigint) RETURNS boolean AS $$
DECLARE
  id alias for $1;
  geom geometry;
  tags hstore;
  min_admin_level float;
BEGIN
  -- get tags
  tags:=tags_merge(way_assemble_tags(id), (select tags_merge(to_array(osm_tags)) as osm_tags from relation_members rm join osm_rel on 'rel_'||rm.relation_id=osm_rel.osm_id where id=rm.member_id and rm.member_type='W' group by rm.member_id));

  min_admin_level:=parse_lowest_number(tags->'admin_level');

  if min_admin_level is null then
    return false;
  end if;

  geom:=way_get_geom(id);

  -- raise notice 'assemble_boundary(%): level=% full level=(%)', id, min_admin_level, tags->'admin_level';
   
  insert into osm_boundary
    values (
      'boundary_'||id,
      tags,
      min_admin_level,
      ST_Transform(geom, 900913)
    );
  
  return true;
end;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION boundary_update_delete() RETURNS boolean AS $$
DECLARE
  num_rows  int;
BEGIN
  delete from osm_boundary using
    (select id from actions where data_type='W'
     union
     select member_id from actions join relation_members rm on rm.relation_id=actions.id and member_type='W' where actions.data_type='R'
     ) actions 
  where osm_id='boundary_'||id;

  GET DIAGNOSTICS num_rows := ROW_COUNT;
  raise notice 'deleted from osm_boundary (%)', num_rows;

  return true;
END;
$$ language 'plpgsql';


CREATE OR REPLACE FUNCTION boundary_update_insert() RETURNS boolean AS $$
DECLARE
  num_rows  int;
BEGIN
  perform assemble_boundary(id) from
    (select way_id as id from actions join way_tags on actions.id=way_tags.way_id and actions.data_type='W' and actions.action not in ('D') where k='boundary' and v in ('administrative', 'political')
    union
    select relation_members.member_id from actions join relation_tags on actions.id=relation_tags.relation_id and actions.data_type='R' and actions.action not in ('D') join relation_members on relation_tags.relation_id=relation_members.relation_id and relation_members.member_type='W' where k='boundary' and v in ('administrative', 'political')
    ) x;

  GET DIAGNOSTICS num_rows := ROW_COUNT;
  raise notice 'inserted to osm_boundary (%)', num_rows;

  return true;
END;
$$ language 'plpgsql';

select register_hook('osmosis_update_delete', 'boundary_update_delete', 0);
select register_hook('osmosis_update_insert', 'boundary_update_insert', 0);
