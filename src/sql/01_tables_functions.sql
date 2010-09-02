CREATE OR REPLACE FUNCTION way_get_geom(bigint) RETURNS geometry AS $$
DECLARE
  id alias for $1;
BEGIN
  -- raise notice 'way_get_geom(%)', id;
  return (select MakeLine(geom) from (select geom from way_nodes join nodes on node_id=nodes.id where way_id=id order by sequence_id) as x);
END;
$$ LANGUAGE plpgsql stable;

CREATE OR REPLACE FUNCTION node_assemble_tags(bigint) RETURNS hstore AS $$
DECLARE
  id alias for $1;
BEGIN
  -- raise notice 'node_assemble_tags(%)', id;
  return
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from node_tags
      where id=node_id and k!='created_by'
      group by node_id);
END;
$$ LANGUAGE plpgsql stable;

CREATE OR REPLACE FUNCTION way_assemble_tags(bigint) RETURNS hstore AS $$
DECLARE
  id alias for $1;
BEGIN
  -- raise notice 'way_assemble_tags(%)', id;
  return
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from way_tags
      where id=way_id and k!='created_by'
      group by way_id);
END;
$$ LANGUAGE plpgsql stable;

CREATE OR REPLACE FUNCTION rel_assemble_tags(bigint) RETURNS hstore AS $$
DECLARE
  id alias for $1;
BEGIN
  -- raise notice 'rel_assemble_tags(%)', id;
  return
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from relation_tags
      where id=relation_id and k!='created_by'
      group by relation_id);
END;
$$ LANGUAGE plpgsql stable;

CREATE OR REPLACE FUNCTION assemble_multipolygon(bigint) RETURNS boolean AS $$
DECLARE
  id alias for $1;
  geom geometry;
  tags hstore;
  outer_members bigint[];
BEGIN
  -- raise notice 'assemble_multipolygon(%)', id;

  outer_members:=(select to_intarray(member_id) from relation_members where relation_id=id and member_type='W' and member_role='outer' group by relation_id);

  geom:=build_multipolygon(
    (select to_array(way_get_geom(member_id)) from relation_members where relation_id=id and member_type='W' and member_role='outer' group by relation_id),
    (select to_array(way_get_geom(member_id)) from relation_members where relation_id=id and member_type='W' and member_role='inner' group by relation_id));

  tags:=rel_assemble_tags(id);
  if(array_upper(outer_members, 1)=1) then
    tags:=tags_merge(tags, way_assemble_tags(outer_members[1]));
  end if;

  insert into osm_polygon
    values (
      'rel_'||id,
      'rel_'||id,
      tags,
      ST_SetSRID(geom, 900913)
    );

  return true;
END;
$$ LANGUAGE plpgsql;
