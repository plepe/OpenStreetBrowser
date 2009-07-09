insert into search select name, name, '', 'node', osm_id, 'amenity', amenity from planet_osm_point where name is not null and amenity is not null;
insert into search select name, name, '', 'way', osm_id, 'amenity', amenity from planet_osm_polygon where name is not null and amenity is not null;

insert into search select name, name, '', 'node', osm_id, 'shop', shop from planet_osm_point where name is not null and shop is not null;
insert into search select name, name, '', 'way', osm_id, 'shop', shop from planet_osm_polygon where name is not null and shop is not null;

