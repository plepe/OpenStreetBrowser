drop table if exists osm_boundary;
create table osm_boundary (
  osm_id		text		not null,
  osm_tags		hstore		null,
  admin_level		int		not null,
  rel_ids		text[]		default Array[]::text[],
  primary key(osm_id)
);
select AddGeometryColumn('osm_boundary', 'osm_way', 900913, 'LINESTRING', 2);

drop table if exists osm_boundary_update;
create table osm_boundary_update (
  id		bigint		not null,
  osm_id	text		not null
);
 
select assemble_boundary(id) from
  (select way_id as id from way_tags where k='boundary' and v in ('administrative', 'political')
  union
  select relation_members.member_id from relation_tags join relation_members on relation_tags.relation_id=relation_members.relation_id and relation_members.member_type='W' where k='boundary' and v in ('administrative', 'political')
) x;

create index osm_boundary_way_tags on osm_boundary using gist(osm_way, osm_tags);
create index osm_boundary_rel_ids on osm_boundary using gin(rel_ids);
