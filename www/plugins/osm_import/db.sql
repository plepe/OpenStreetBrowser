create index node_tags_k_id on node_tags(k, node_id);
create index way_tags_k_id on way_tags(k, way_id);
create index relation_tags_k_id on relation_tags(k, relation_id);

create index relations_members_pk on relation_members (relation_id);
create index relations_members_mem on relation_members (member_id);
create index relations_members_type on relation_members (member_type);
create index relations_members_role on relation_members (member_role);

create index way_nodes_seq_id on way_nodes("sequence_id");

-- point
create table !schema:osm!.osm_point (
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
create table !schema:osm!.osm_line (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_line', 'osm_way', 900913, 'LINESTRING', 2);

create table !schema:osm!.osm_polygon (
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
create table !schema:osm!.osm_rel (
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

-- osm_all_* build the osm_all view

-- drop all views
drop view if exists osm_all;

drop view if exists osm_poipoly;
drop view if exists osm_allrel;
drop view if exists osm_linepoly;

drop view if exists osm_all_point;
drop view if exists osm_all_line;
drop view if exists osm_all_polygon;
drop view if exists osm_all_rel;

-- osm_all_point
create view !schema:osm!.osm_all_point as (
  select
    "osm_id",
    'type=>node, form=>point'::hstore as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    "osm_way" as "osm_way_point",
    ST_MakeLine("osm_way", "osm_way") as "osm_way_line",
    ST_MakePolygon(ST_MakeLine(Array["osm_way", "osm_way", "osm_way", "osm_way"])) as "osm_way_polygon"
  from osm_point
);

-- osm_all_line
create view !schema:osm!.osm_all_line as (
  select
    "osm_id",
    'type=>way, form=>line'::hstore as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Line_Interpolate_Point("osm_way", 0.5) as "osm_way_point",
    "osm_way" as "osm_way_line",
    null::geometry as "osm_way_polygon"
  from osm_line
);

-- osm_all_polygon
create view !schema:osm!.osm_all_polygon as (
  select
    "osm_id",
    (CASE
      WHEN rel_id is not null THEN 'type=>rel, form=>polygon'::hstore 
      ELSE 'type=>way, form=>polygon'::hstore 
    END) as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Centroid("osm_way") as "osm_way_point",
    ST_Boundary("osm_way") as "osm_way_line",
    "osm_way" as "osm_way_polygon"
  from osm_polygon
);

-- osm_all_rel
create view !schema:osm!.osm_all_rel as (
  select
    "osm_id",
    'type=>rel, form=>special'::hstore as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    ST_CollectionExtract("osm_way", 1) as "osm_way_point",
    ST_CollectionExtract("osm_way", 2) as "osm_way_line",
    ST_CollectionExtract("osm_way", 3) as "osm_way_polygon"
  from osm_rel
);

-- osm_all
create view !schema:osm!.osm_all as (
  select * from osm_all_point
  union all
  select * from osm_all_line
  union all
  select * from osm_all_polygon
  union all
  select * from osm_all_rel
);

-- osm_poipoly
create view !schema:osm!.osm_poipoly as (
  select * from osm_all_point
  union all
  select * from osm_all_polygon
);

-- osm_linepoly
create view !schema:osm!.osm_linepoly as (
  select * from osm_all_line
  union all
  select * from osm_all_polygon
);

-- osm_all_rel
create view !schema:osm!.osm_allrel as (
  select * from osm_all_polygon
  union all
  select * from osm_all_rel
);

-- osm_rel_members
drop view osm_rel_members;
create view !schema:osm!.osm_rel_members as (
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
