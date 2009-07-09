drop table if exists planet_osm_polygon_extract;
create table planet_osm_polygon_extract (
  osm_id	int4	not null,
  name		text	,
  network	text	null,
  amenity	text	null,
  "natural"	text	null,
  leisure	text	null,
  landuse	text	null,
  historic	text	null,
  shop		text	null,
  aeroway	text,
  military	text,
  power		text,
  tourism	text,
  building	text,
  way_area	real	null,
  primary key(osm_id)
);
SELECT AddGeometryColumn('planet_osm_polygon_extract', 'way', 900913, 'POLYGON', 2);

insert into planet_osm_polygon_extract
  select osm_id, name, network, amenity, "natural", leisure, landuse, historic, shop, aeroway, military, power, tourism, building, way_area, way
    from planet_osm_polygon
    where ("natural" is not null or leisure is not null or 
           "landuse" is not null or amenity is not null or
	   "aeroway" is not null or "military" is not null or
	   "power" is not null or "tourism" is not null or "shop" is not null) 
      and way_area>1000000;

create index planet_osm_polygon_extract_way on planet_osm_polygon_extract using gist(way);

drop table if exists planet_osm_line_extract;
create table planet_osm_line_extract (
  osm_id	int4	not null,
  name		text	,
  network	text	null,
  highway	text,
  waterway	text,
  aeroway	text,
  railway	text,
  barrier	text,
  power		text,
  man_made	text,
  "natural"	text,
  importance	text,
  primary key(osm_id)
);
SELECT AddGeometryColumn('planet_osm_line_extract', 'way', 900913, 'LINESTRING', 2);

insert into planet_osm_line_extract
  select osm_id, name, network, highway, waterway, railway, barrier, "natural",
    importance, way
  from planet_osm_line
  where ("highway" in ('motorway', 'trunk', 'primary', 'secondary', 'tertiary') or
         "railway" in ('rail') or
	 ("power" in ('line') and "importance" in ('international', 'national')) or
	 "man_made" in ('pipeline') or
	 "aeroway" in ('runway') or
	 "waterway" in ('river', 'canal'))
    and osm_id>=0;

create index planet_osm_line_extract_way on planet_osm_line_extract using gist(way);
