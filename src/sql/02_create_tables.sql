drop table if exists osm_point;
create table osm_point (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_point', 'osm_way', 900913, 'POINT', 2);

insert into osm_point
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

create index osm_point_tags on osm_point using gin(osm_tags);
create index osm_point_way  on osm_point using gist(osm_way);

drop table if exists osm_line;
create table osm_line (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_line', 'osm_way', 900913, 'LINESTRING', 2);

insert into osm_line
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

create index osm_line_tags on osm_line using gin(osm_tags);
create index osm_line_way  on osm_line using gist(osm_way);

drop table if exists osm_rel;
create table osm_rel (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_rel', 'osm_way', 900913, 'GEOMETRY', 2);

insert into osm_rel
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
	    from osm_line w inner join relation_members rm on w.osm_id='way_'||rm.member_id and rm.member_type='W'
	    where rm.relation_id=relations.id) c)) as osm_way
    from relations;

create index osm_rel_tags on osm_rel using gin(osm_tags);
create index osm_rel_way  on osm_rel using gist(osm_way);

drop table if exists osm_polygon;
create table osm_polygon (
  osm_id		text		not null,
  rel_id		text		null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_polygon', 'osm_way', 900913, 'GEOMETRY', 2);

insert into osm_polygon
  select
    osm_line.osm_id,
    null,
    osm_line.osm_tags,
    MakePolygon(osm_line.osm_way) as osm_way
  from
    osm_line
  where
    IsClosed(osm_line.osm_way) and
    NPoints(osm_line.osm_way)>3 and

    array_upper((
      select
        to_textarray(osm_rel.osm_id)
      from
        relation_members
	join osm_rel on
	  'rel_'||relation_id=osm_rel.osm_id and
	  osm_rel.osm_tags @> 'type=>multipolygon'
      where
	member_type='W' and
	member_role in ('outer', '') and
        member_id=cast((string_to_array(osm_line.osm_id, '_'))[2] as bigint)
    ), 1) is null;

insert into osm_polygon
  select
    (CASE 
      WHEN ways_outer.count=1 THEN osm_rel.osm_id||';'||array_to_string(ways_outer.osm_id, ';')
      ELSE osm_rel.osm_id
    END) as osm_id,
    osm_rel.osm_id as rel_id,
    (CASE 
      WHEN ways_outer.count=1 THEN tags_merge(Array[osm_rel.osm_tags, ways_outer.osm_tags])
      ELSE osm_rel.osm_tags
    END) as osm_tags,
    build_multipolygon(ways_outer.osm_way, ways_inner.osm_way) as osm_way
  from
    osm_rel
    left join
    (select
       'rel_'||relation_members.relation_id as rel_id,
       to_textarray(osm_id) as osm_id,
       tags_merge(to_array(osm_tags)) as osm_tags,
       to_array(osm_way) as osm_way,
       count(*) as count
     from
       relation_members
       join osm_line on
         'way_'||member_id=osm_line.osm_id and
	 member_type='W' and member_role in ('outer', '')
     group by relation_members.relation_id
    ) ways_outer on
      osm_rel.osm_id=ways_outer.rel_id
    left join
    (select
       'rel_'||relation_members.relation_id as rel_id,
       to_array(osm_way) as osm_way
     from
       relation_members
       join osm_line on
         'way_'||member_id=osm_line.osm_id and
	 member_type='W' and member_role='inner'
     group by relation_members.relation_id
    ) ways_inner on
      osm_rel.osm_id=ways_inner.rel_id
  where
    osm_rel.osm_tags @> 'type=>multipolygon' and
    ways_outer.osm_id is not null;

create index osm_polygon_rel_id on osm_polygon(rel_id);
create index osm_polygon_tags on osm_polygon using gin(osm_tags);
create index osm_polygon_way  on osm_polygon using gist(osm_way);
