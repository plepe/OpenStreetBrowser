-- point
drop table if exists osm_point;
create table osm_point (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_point', 'osm_way', 900913, 'POINT', 2);
 
select * from assemble_point(id) from nodes;
 
create index osm_point_tags on osm_point using gin(osm_tags);
create index osm_point_way  on osm_point using gist(osm_way);
create index osm_point_way_tags on osm_point using gist(osm_way, osm_tags);

-- line
drop table if exists osm_line;
create table osm_line (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_line', 'osm_way', 900913, 'LINESTRING', 2);

select assemble_line(id) from ways;

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

drop view osm_rel_members;
create view osm_rel_members as (
  select
    osm_rel.osm_id,
    osm_line.osm_id as member_id,
    osm_rel.member_ids as rel_member_ids,
    member_role,
    osm_rel.osm_tags as osm_tags,
    osm_line.osm_tags as member_tags,
    osm_rel.osm_way as osm_way,
    osm_line.osm_way as member_way
  from (
    select
      osm_rel.*,
      unnest(member_ids) as member_id,
      unnest(member_roles) as member_role
    from osm_rel) osm_rel
    join osm_line
      on osm_line.osm_id=osm_rel.member_id
);

-- polygon
drop table if exists osm_polygon;
create table osm_polygon (
  osm_id		text		not null,
  rel_id		text		null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_polygon', 'osm_way', 900913, 'GEOMETRY', 2);

select assemble_polygon(id) from 
  (select
    ways.id, 
    (select node_id from way_nodes where way_id=ways.id and sequence_id=0) as first_node,
    (select node_id from way_nodes where way_id=ways.id order by sequence_id desc limit 1) as last_node
    from ways) c
  where
    first_node=last_node;

select
  assemble_multipolygon(relation_id)
from relation_tags
where k='type' and v='multipolygon';

create index osm_polygon_rel_id on osm_polygon(rel_id);
create index osm_polygon_tags on osm_polygon using gin(osm_tags);
create index osm_polygon_way  on osm_polygon using gist(osm_way);
create index osm_polygon_way_tags on osm_polygon using gist(osm_way, osm_tags);

-- all
drop view if exists osm_all;
create view osm_all as (
  select osm_id, 'point' as osm_type, osm_tags, osm_way from osm_point
  union all
  select osm_id, 'line' as osm_type, osm_tags, osm_way from osm_line
  union all
  select osm_id, 'polygon' as osm_type, osm_tags, osm_way from osm_polygon
  union all
  select osm_id, 'rel' as osm_type, osm_tags, osm_way from osm_rel
);

-- all
drop view if exists osm_poipoly;
create view osm_poipoly as (
  select osm_id, 'point' as osm_type, osm_tags, osm_way from osm_point
  union all
  select osm_id, 'polygon' as osm_type, osm_tags, osm_way from osm_polygon
);
