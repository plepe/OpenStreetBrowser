--register_hook('osmosis_update_delete', 'boundary_update_delete');
--register_hook('osmosis_update_finish', 'boundary_update_finish');

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
