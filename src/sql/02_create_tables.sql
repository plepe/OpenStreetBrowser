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

select assemble_rel(id) from relations;

create index osm_rel_tags on osm_rel using gin(osm_tags);
create index osm_rel_way  on osm_rel using gist(osm_way);
create index osm_rel_way_tags on osm_rel using gist(osm_way, osm_tags);

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
