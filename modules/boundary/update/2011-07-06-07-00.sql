alter table osm_boundary add column rel_ids text[] default Array[]::text[];
create index osm_boundary_rel_ids on osm_boundary using gin(rel_ids);
