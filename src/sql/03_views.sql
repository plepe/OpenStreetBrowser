-- osm_all_* build the osm_all view
-- osm_all_point
drop view if exists osm_all;
drop view if exists osm_all_point;
create view osm_all_point as (
  select
    "osm_id",
    'point'::text as "osm_type", 
    'point'::text as "osm_form", 
    "osm_tags",
    "osm_way" as "osm_way",
    "osm_way" as "osm_way_point",
    ST_MakeLine("osm_way", "osm_way") as "osm_way_line",
    ST_MakePolygon(ST_MakeLine(Array["osm_way", "osm_way", "osm_way", "osm_way"])) as "osm_way_polygon"
  from osm_point
);

-- osm_all_line
drop view if exists osm_all_line;
create view osm_all_line as (
  select
    "osm_id",
    'line'::text as "osm_type", 
    'line'::text as "osm_form", 
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Line_Interpolate_Point("osm_way", 0.5) as "osm_way_point",
    "osm_way" as "osm_way_line",
    null::geometry as "osm_way_polygon"
  from osm_line
);

-- osm_all_polygon
drop view if exists osm_all_polygon;
create view osm_all_polygon as (
  select
    "osm_id",
    'polygon'::text as "osm_type", 
    'polygon'::text as "osm_form", 
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Centroid("osm_way") as "osm_way_point",
    ST_Boundary("osm_way") as "osm_way_line",
    "osm_way" as "osm_way_polygon"
  from osm_polygon
);

-- osm_all_rel
drop view if exists osm_all_rel;
create view osm_all_rel as (
  select
    "osm_id",
    'rel'::text as "osm_type", 
    'special'::text as "osm_form", 
    "osm_tags",
    "osm_way" as "osm_way",
    ST_Centroid("osm_way") as "osm_way_point",
    "osm_way" as "osm_way_line",
    "osm_way" as "osm_way_polygon"
  from osm_rel
);

-- osm_all
create view osm_all as (
  select * from osm_all_point
  union all
  select * from osm_all_line
  union all
  select * from osm_all_polygon
  union all
  select * from osm_all_rel
);

-- osm_poipoly
drop view if exists osm_poipoly;
create view osm_poipoly as (
  select osm_id, 'point' as osm_type, osm_tags, osm_way from osm_point
  union all
  select osm_id, 'polygon' as osm_type, osm_tags, osm_way from osm_polygon
);

-- osm_linepoly
drop view if exists osm_linepoly;
create view osm_linepoly as (
  select osm_id, 'line' as osm_type, osm_tags, osm_way, osm_way as "osm_way_line" from osm_line
  union all
  select osm_id, 'polygon' as osm_type, osm_tags, osm_way, ST_Boundary(osm_way) as "osm_way_line" from osm_polygon
);

-- osm_all_rel
drop view if exists osm_allrel;
create view osm_allrel as (
  select osm_id, 'rel' as osm_type, osm_tags, osm_way, member_ids, member_roles from osm_rel
  union all
  select osm_id, 'polygon' as osm_type, osm_tags, osm_way, member_ids, member_roles from osm_polygon
);

-- osm_rel_members
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


