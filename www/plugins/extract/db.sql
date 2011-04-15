drop table if exists osm_point_extract;
create table osm_point_extract (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_point_extract', 'osm_way', 900913, 'GEOMETRY', 2);

drop table if exists osm_line_extract;
create table osm_line_extract (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_line_extract', 'osm_way', 900913, 'GEOMETRY', 2);

drop table if exists osm_polygon_extract;
create table osm_polygon_extract (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_polygon_extract', 'osm_way', 900913, 'GEOMETRY', 2);

select extract_init();

create index concurrently osm_extract_point_way_tags on osm_point_extract using gist(osm_way, osm_tags);
create index concurrently osm_extract_line_way_tags on osm_line_extract using gist(osm_way, osm_tags);
create index concurrently osm_extract_polygon_way_tags on osm_polygon_extract using gist(osm_way, osm_tags);
