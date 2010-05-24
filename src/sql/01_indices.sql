create index node_tags_k on node_tags(k);
create index way_tags_k on way_tags(k);
create index relation_tags_k on relation_tags(k);

create index relations_members_pk on relation_members (relation_id);
create index relations_members_mem on relation_members (member_id);
create index relations_members_type on relation_members (member_type);
create index relations_members_role on relation_members (member_role);

create index planet_osm_line_zorder on planet_osm_line(z_order);
create index planet_osm_polygon_zorder on planet_osm_polygon(z_order);

create index planet_osm_polygon_waterway on planet_osm_polygon("waterway");
create index planet_osm_polygon_natural on planet_osm_polygon("natural");
create index planet_osm_polygon_landuse on planet_osm_polygon("landuse");
create index planet_osm_polygon_highway on planet_osm_polygon("highway");

create index planet_osm_line_waterway on planet_osm_line("waterway");
create index planet_osm_line_railway on planet_osm_line("railway");
create index planet_osm_line_highway on planet_osm_line("highway");
create index planet_osm_line_barrier on planet_osm_line("barrier");
create index planet_osm_line_natural on planet_osm_line("natural");
create index planet_osm_line_power on planet_osm_line("power");
create index planet_osm_line_man_made on planet_osm_line("man_made");
create index planet_osm_line_boundary on planet_osm_line(boundary);
create index planet_osm_line_smoothness on planet_osm_line("smoothness");
create index planet_osm_line_impassable on planet_osm_line("impassable");

create index planet_osm_point_waterway on planet_osm_point("waterway");
create index planet_osm_point_railway on planet_osm_point("railway");
create index planet_osm_point_highway on planet_osm_point("highway");
create index planet_osm_point_barrier on planet_osm_point("barrier");
create index planet_osm_point_power on planet_osm_point("power");
create index planet_osm_point_man_made on planet_osm_point("man_made");
create index planet_osm_point_sport on planet_osm_point("sport");
create index planet_osm_point_leisure on planet_osm_point("leisure");

create index way_nodes_seq_id on way_nodes("sequence_id");

create index planet_osm_line_addr_street on planet_osm_line("addr:street");
create index planet_osm_point_addr_street on planet_osm_point("addr:street");
create index planet_osm_point_addr_num on planet_osm_point("addr:housenumber");
create index planet_osm_polygon_addr_street on planet_osm_polygon("addr:street");
create index planet_osm_polygon_addr_num on planet_osm_polygon("addr:housenumber");

create index planet_osm_point_amenity  on planet_osm_point(amenity);
create index planet_osm_point_shop     on planet_osm_point(shop);
create index planet_osm_point_tourism  on planet_osm_point(tourism);
create index planet_osm_point_historic on planet_osm_point(historic);
create index planet_osm_point_landuse  on planet_osm_point(landuse);
create index planet_osm_point_cemetery on planet_osm_point(cemetery);

create index planet_osm_polygon_amenity  on planet_osm_polygon("amenity");
create index planet_osm_polygon_shop     on planet_osm_polygon("shop");
create index planet_osm_polygon_tourism  on planet_osm_polygon("tourism");
create index planet_osm_polygon_historic on planet_osm_polygon("historic");
create index planet_osm_polygon_landuse  on planet_osm_polygon("landuse");
create index planet_osm_polygon_cemetery on planet_osm_polygon("cemetery");
create index planet_osm_polygon_power    on planet_osm_polygon("power");
create index planet_osm_polygon_man_made on planet_osm_polygon("man_made");
create index planet_osm_polygon_name	 on planet_osm_polygon("name");
create index planet_osm_polygon_sport    on planet_osm_polygon("sport");
create index planet_osm_polygon_leisure  on planet_osm_polygon("leisure");

create table indexes (
  _table		text	not null,
  _key		text	not null,
  _type		text	not null,
  id		text	not null,
  primary key(_table, _key, _type, id)
);
