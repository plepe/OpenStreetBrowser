drop table if exists osm_nodes;
create table osm_nodes (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_nodes', 'osm_way', 900913, 'POINT', 2);

insert into osm_nodes
  select * from (select
    'node_'||id as osm_id,
    (select
	array_to_hstore(to_textarray(k), to_textarray(v))
      from node_tags
      where id=node_id and k!='created_by'
      group by node_id) as osm_tags,
    ST_Transform(geom, 900913) as osm_way
    from nodes) as x
  where (array_dims(akeys(osm_tags)))!='[1:0]';

create index osm_nodes_tags on osm_nodes using gin(osm_tags);
create index osm_nodes_way  on osm_nodes using gist(osm_way);

drop table if exists osm_ways;
create table osm_ways (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_ways', 'osm_way', 900913, 'LINESTRING', 2);

insert into osm_ways
  SELECT
    'way_'||id as osm_id,
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

create index osm_ways_tags on osm_ways using gin(osm_tags);
create index osm_ways_way  on osm_ways using gist(osm_way);

drop table if exists osm_rels;
create table osm_rels (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_rels', 'osm_way', 900913, 'GEOMETRY', 2);

insert into osm_rels
  select
      'rel_'||id as osm_id,
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
	    from osm_ways w inner join relation_members rm on w.osm_id='way_'||rm.member_id and rm.member_type='W'
	    where rm.relation_id=relations.id) c)) as osm_way
    from relations;

create index osm_rels_tags on osm_rels using gin(osm_tags);
create index osm_rels_way  on osm_rels using gist(osm_way);

drop table if exists osm_polygons;
create table osm_polygons (
  osm_id		text		not null,
  rel_id		text		null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_polygons', 'osm_way', 900913, 'GEOMETRY', 2);

insert into osm_polygons
  select
    osm_ways.osm_id,
    null,
    osm_ways.osm_tags,
    MakePolygon(osm_ways.osm_way) as osm_way
  from
    osm_ways
  where
    IsClosed(osm_ways.osm_way) and
    (select
       to_textarray(osm_rels.osm_id)
     from
       osm_rels
       join relation_members on
         osm_rels.osm_id='rel_'||relation_members.relation_id
     where
       osm_rels.osm_tags @> 'type=>multipolygon' and
       'way_'||relation_members.member_id=osm_ways.osm_id and
       relation_members.member_type='W' and
       relation_members.member_role!='inner'
    ) is null;

insert into osm_polygons
  select
    osm_rels.osm_id||';'||array_to_string(ways_outer.osm_id, ';'),
    osm_rels.osm_id,
    tags_merge(Array[osm_rels.osm_tags, ways_outer.osm_tags]),
    build_multipolygon(ways_outer.osm_way, ways_inner.osm_way) as osm_way
  from
    osm_rels
    left join
    (select
       'rel_'||relation_members.relation_id as rel_id,
       to_textarray(osm_id) as osm_id,
       tags_merge(to_array(osm_tags)) as osm_tags,
       to_array(osm_way) as osm_way
     from
       relation_members
       join osm_ways on
         'way_'||member_id=osm_ways.osm_id and
	 member_type='W' and member_role in ('outer', '')
     group by relation_members.relation_id
    ) ways_outer on
      osm_rels.osm_id=ways_outer.rel_id
    left join
    (select
       'rel_'||relation_members.relation_id as rel_id,
       to_array(osm_way) as osm_way
     from
       relation_members
       join osm_ways on
         'way_'||member_id=osm_ways.osm_id and
	 member_type='W' and member_role='inner'
     group by relation_members.relation_id
    ) ways_inner on
      osm_rels.osm_id=ways_inner.rel_id
  where
    osm_rels.osm_tags @> 'type=>multipolygon' and
    ways_outer.osm_id is not null;

create index osm_polygons_rel_id on osm_polygons(rel_id);
create index osm_polygons_tags on osm_polygons using gin(osm_tags);
create index osm_polygons_way  on osm_polygons using gist(osm_way);
