CREATE OR REPLACE FUNCTION assemble_boundary(bigint) RETURNS boolean AS $$
DECLARE
  id alias for $1;
  geom geometry;
  tags hstore;
  min_admin_level float;
  rel_ids bigint[];
BEGIN
  -- get list of relations which are boundary=administrative or =political
  rel_ids:=(select array_agg(rm.relation_id)
   from relation_members rm
     join relation_tags rt on rm.relation_id=rt.relation_id
   where id=rm.member_id and rm.member_type='W'
     and rt.k='boundary' and rt.v in ('administrative', 'political'));

  -- get tags
  tags:=tags_merge(
    (select way_assemble_tags(id)
     from way_tags
     where way_id=id
       and k='boundary' and v in ('administrative', 'political'))
  ,
    (select tags_merge(array_agg(rel_assemble_tags(rel_id)))
     from (select unnest(rel_ids) as rel_id) x)
  );

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
      (select array_agg('rel_'||rel_id) from (select unnest(rel_ids) as rel_id) x),
      ST_Transform(geom, 900913)
    );
  
  return true;
end;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION boundary_update_delete() RETURNS boolean AS $$
DECLARE
  num_rows  int;
BEGIN
  -- save all ids we are going to delete in osm_boundary_update
  insert into osm_boundary_update
    (select id, osm_id from (select id from actions where data_type='W'
     union
     select member_id from actions join relation_members rm on rm.relation_id=actions.id and member_type='W' where actions.data_type='R'
     ) actions join osm_boundary on osm_id='boundary_'||id);

  -- now delete everything
  delete from osm_boundary using osm_boundary_update
    where osm_boundary.osm_id=osm_boundary_update.osm_id;

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
    -- all ways which are boundaries and have been changed ...
    (select way_id as id from actions join way_tags on actions.id=way_tags.way_id and actions.data_type='W' and actions.action not in ('D') where k='boundary' and v in ('administrative', 'political')
    union
    -- all relations which are boundaries and have been changed ...
    select relation_members.member_id from actions join relation_tags on actions.id=relation_tags.relation_id and actions.data_type='R' and actions.action not in ('D') join relation_members on relation_tags.relation_id=relation_members.relation_id and relation_members.member_type='W' where k='boundary' and v in ('administrative', 'political')
    union
    -- and to be sure all ways we deleted
    select id from osm_boundary_update
    ) x;

  GET DIAGNOSTICS num_rows := ROW_COUNT;
  raise notice 'inserted to osm_boundary (%)', num_rows;

  -- delete everything from temporary table
  delete from osm_boundary_update;

  return true;
END;
$$ language 'plpgsql';

select register_hook('osmosis_update_delete', 'boundary_update_delete', 0);
select register_hook('osmosis_update_insert', 'boundary_update_insert', 0);
