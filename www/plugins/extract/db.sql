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

create index osm_extract_point_way_tags on osm_point_extract using gist(osm_way, osm_tags);
create index osm_extract_line_way_tags on osm_line_extract using gist(osm_way, osm_tags);
create index osm_extract_polygon_way_tags on osm_polygon_extract using gist(osm_way, osm_tags);

-- drop all views
drop view if exists osm_all_extract;

drop view if exists osm_all_point_extract;
drop view if exists osm_all_line_extract;
drop view if exists osm_all_polygon_extract;

-- osm_all_point
create view osm_all_point_extract as (
  select
    "osm_id",
    'type=>node, form=>point'::hstore as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    "osm_way" as "osm_way_point",
    ST_MakeLine("osm_way", "osm_way") as "osm_way_line",
    ST_MakePolygon(ST_MakeLine(Array["osm_way", "osm_way", "osm_way", "osm_way"])) as "osm_way_polygon"
  from osm_point_extract
);

-- osm_all_line
create view osm_all_line_extract as (
  select
    "osm_id",
    'type=>way, form=>line'::hstore as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Line_Interpolate_Point("osm_way", 0.5) as "osm_way_point",
    "osm_way" as "osm_way_line",
    null::geometry as "osm_way_polygon"
  from osm_line_extract
);

-- osm_all_polygon
create view osm_all_polygon_extract as (
  select
    "osm_id",
    'form=>polygon'::hstore as "osm_type",
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Centroid("osm_way") as "osm_way_point",
    ST_Boundary("osm_way") as "osm_way_line",
    "osm_way" as "osm_way_polygon"
  from osm_polygon_extract
);

-- osm_all
create view osm_all_extract as (
  select * from osm_all_point_extract
  union all
  select * from osm_all_line_extract
  union all
  select * from osm_all_polygon_extract
);
