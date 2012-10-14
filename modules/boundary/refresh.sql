-- Generate all boundaries which are missing in the database
select y.id, assemble_boundary(y.id)
from (
  select x.id, osm_boundary.osm_id
  from (
      select way_id as id
      from way_tags
      where k='boundary' and v in ('administrative', 'political')
    union
      select relation_members.member_id
      from relation_tags
        join relation_members on relation_tags.relation_id=relation_members.relation_id and relation_members.member_type='W'
      where k='boundary' and v in ('administrative', 'political')
  ) x
  left join osm_boundary on 'boundary_'||x.id=osm_boundary.osm_id
  where osm_boundary.osm_id is null
) y;
