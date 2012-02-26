create index node_tags_k_id on node_tags(k, node_id);
create index way_tags_k_id on way_tags(k, way_id);
create index relation_tags_k_id on relation_tags(k, relation_id);

create index relations_members_pk on relation_members (relation_id);
create index relations_members_mem on relation_members (member_id);
create index relations_members_type on relation_members (member_type);
create index relations_members_role on relation_members (member_role);

create index way_nodes_seq_id on way_nodes("sequence_id");

-- point
drop table if exists osm_point;
create table osm_point (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_point', 'osm_way', 900913, 'POINT', 2);
 
select assemble_point(id) from nodes;
 
create index osm_point_tags on osm_point using gin(osm_tags);
create index osm_point_way  on osm_point using gist(osm_way);
create index osm_point_way_tags on osm_point using gist(osm_way, osm_tags);

-- ways -> osm_line and osm_polygon
drop table if exists osm_line;
create table osm_line (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_line', 'osm_way', 900913, 'LINESTRING', 2);

drop table if exists osm_polygon;
create table osm_polygon (
  osm_id		text		not null,
  rel_id		text		null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_polygon', 'osm_way', 900913, 'GEOMETRY', 2);
alter table osm_polygon
  add column	member_ids		text[]		null,
  add column	member_roles		text[]		null;

select assemble_way(id) from ways;

create index osm_line_tags on osm_line using gin(osm_tags);
create index osm_line_way  on osm_line using gist(osm_way);
create index osm_line_way_tags on osm_line using gist(osm_way, osm_tags);

-- rel
drop table if exists osm_rel;
create table osm_rel (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_rel', 'osm_way', 900913, 'GEOMETRY', 2);
alter table osm_rel
  add column	member_ids		text[]		null,
  add column	member_roles		text[]		null;

select assemble_rel(id) from relations;

create index osm_rel_tags on osm_rel using gin(osm_tags);
create index osm_rel_way  on osm_rel using gist(osm_way);
create index osm_rel_way_tags on osm_rel using gist(osm_way, osm_tags);
create index osm_rel_members_idx on osm_rel using gin(member_ids);

select
  assemble_multipolygon(relation_id)
from relation_tags
where k='type' and v in ('multipolygon', 'boundary');

create index osm_polygon_rel_id on osm_polygon(rel_id);
create index osm_polygon_tags on osm_polygon using gin(osm_tags);
create index osm_polygon_way  on osm_polygon using gist(osm_way);
create index osm_polygon_way_tags on osm_polygon using gist(osm_way, osm_tags);
create index osm_polygon_members_idx on osm_polygon using gin(member_ids);
