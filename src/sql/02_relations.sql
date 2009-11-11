--alter table planet_osm_rels add column node_parts int[] null;
--update planet_osm_rels set node_parts=(select to_intarray(member_id) from relation_members where member_type='1' and relation_members.relation_id=planet_osm_rels.id group by relation_id);
--
--alter table planet_osm_rels add column way_parts int[] null;
--update planet_osm_rels set way_parts=(select to_intarray(member_id) from relation_members where member_type='2' and relation_members.relation_id=planet_osm_rels.id group by relation_id);
--
--alter table planet_osm_rels add column rel_parts int[] null;
--update planet_osm_rels set rel_parts=(select to_intarray(member_id) from relation_members where member_type='3' and relation_members.relation_id=planet_osm_rels.id group by relation_id);

alter table planet_osm_rels add column name text;
update planet_osm_rels set name=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='name' limit 1);

alter table planet_osm_rels add column route text;
update planet_osm_rels set route=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='route' limit 1);

alter table planet_osm_rels add column type text;
update planet_osm_rels set type=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='type' limit 1);

alter table planet_osm_rels add column ref text;
update planet_osm_rels set ref=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='ref' limit 1);

alter table planet_osm_rels add column network text;
update planet_osm_rels set network=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='network' limit 1);

alter table planet_osm_rels add column importance text;
update planet_osm_rels set importance=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='importance' limit 1);

alter table planet_osm_rels add column admin_level text;
update planet_osm_rels set admin_level=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='admin_level' limit 1);

alter table planet_osm_rels add column boundary text;
update planet_osm_rels set boundary=(select v from relation_tags where relation_tags.relation_id=planet_osm_rels.id and k='boundary' limit 1);

create index planet_osm_rels_type on planet_osm_rels(type);
create index planet_osm_rels_route on planet_osm_rels(route);
create index planet_osm_rels_network on planet_osm_rels(network);
create index planet_osm_rels_importance on planet_osm_rels(importance);
