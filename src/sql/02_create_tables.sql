drop table if exists osm_nodes;
create table osm_nodes (
  osm_type		text		not null,
  osm_id		bigint		not null,
  osm_tags		hstore		null,
  primary key(osm_type, osm_id)
);
select AddGeometryColumn('osm_nodes', 'osm_way', 900913, 'POINT', 2);

insert into osm_nodes
  select * from (select
    'node'::text as osm_type,
    id as osm_id,
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from node_tags
      where id=node_id and k!='created_by'
      group by node_id) as osm_tags,
    ST_Transform(geom, 900913) as osm_way
    from nodes) as x
  where (array_dims(akeys(osm_tags)))!='[1:0]';

create index osm_nodes_id   on osm_nodes(osm_id);
create index osm_nodes_tags on osm_nodes using gin(osm_tags);
create index osm_nodes_way  on osm_nodes using gist(osm_way);

drop table if exists osm_ways;
create table osm_ways (
  osm_type		text		not null,
  osm_id		bigint		not null,
  osm_tags		hstore		null,
  primary key(osm_type, osm_id)
);
select AddGeometryColumn('osm_ways', 'osm_way', 900913, 'LINESTRING', 2);

insert into osm_ways
  SELECT 
    'way'::text as osm_type,
    id as osm_id,
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from way_tags
      where id=way_id and k!='created_by'
      group by way_id) as osm_tags,
    (SELECT ST_Transform(MakeLine(c.geom), 900913) AS osm_way FROM (
              SELECT n.geom AS geom
              FROM nodes n INNER JOIN way_nodes wn ON n.id = wn.node_id
              WHERE (wn.way_id = ways.id) ORDER BY wn.sequence_id
      ) c) as osm_way
  from ways group by id;

create index osm_ways_id   on osm_ways(osm_id);
create index osm_ways_tags on osm_ways using gin(osm_tags);
create index osm_ways_way  on osm_ways using gist(osm_way);

drop table if exists osm_rels;
create table osm_rels (
  osm_type		text		not null,
  osm_id		bigint		not null,
  osm_tags		hstore		null,
  primary key(osm_type, osm_id)
);
select AddGeometryColumn('osm_rels', 'osm_way', 900913, 'GEOMETRY', 2);

insert into osm_rels
  select
      'rel'::text as osm_type,
      id as osm_id,
      (select
	  array_to_hstore(to_textarray(k), to_textarray(v))
	from relation_tags
	where id=relation_id and k!='created_by'
	group by relation_id) as osm_tags,
      ST_Collect((select ST_Transform(geom, 900913) from (
	  select ST_Collect(n.geom) as geom
	    from nodes n inner join relation_members rm on n.id=rm.member_id and rm.member_type='N'
	    where rm.relation_id=relations.id) c),
	(select ST_Collect(geom) from (
	  select w.osm_way as geom
	    from osm_ways w inner join relation_members rm on w.osm_id=rm.member_id and rm.member_type='W'
	    where rm.relation_id=relations.id) c)) as osm_way
    from relations;

create index osm_rels_id   on osm_rels(osm_id);
create index osm_rels_tags on osm_rels using gin(osm_tags);
create index osm_rels_way  on osm_rels using gist(osm_way);
