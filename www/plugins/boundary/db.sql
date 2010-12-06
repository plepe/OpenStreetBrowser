drop table if exists osm_boundary;
create table osm_boundary (
  osm_id		text		not null,
  osm_tags		hstore		null,
  admin_level		int		not null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_boundary', 'osm_way', 900913, 'LINESTRING', 2);
 
select assemble_boundary(id) from
  (select way_id as id from way_tags where k='boundary' and v in ('administrative', 'political')
  union
  select relation_members.member_id from osm_rel join relation_members on cast(substr(osm_rel.osm_id, 5) as int)=relation_members.relation_id and relation_members.member_type='W' where osm_tags@>'type=>boundary' and osm_tags@>'boundary=>administrative') x;

create index osm_boundary_way_tags on osm_boundary using gist(osm_way, osm_tags);
