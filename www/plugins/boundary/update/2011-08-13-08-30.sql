drop table if exists osm_boundary_update;
create table osm_boundary_update (
  id		bigint		not null,
  osm_id	text		not null
);
