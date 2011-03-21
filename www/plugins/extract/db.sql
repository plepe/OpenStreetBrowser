drop table if exists osm_all_extract;
create table osm_all_extract (
  osm_id		text		not null,
  osm_tags		hstore		null,
  primary key(osm_id)
);
select AddGeometryColumn('osm_all_extract', 'osm_way', 900913, 'GEOMETRY', 2);
create index osm_all_extract_way_tags on osm_all_extract using gist(osm_way, osm_tags);

delete from classify_hmatch where type='extract';
insert into classify_hmatch values ('extract', 'highway=>motorway'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>motorway_link'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>trunk'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>trunk_link'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>primary'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>primary_link'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>secondary'::hstore, null, '#extract=>roads'::hstore, 0);
insert into classify_hmatch values ('extract', 'highway=>tertiary'::hstore, null, '#extract=>roads'::hstore, 0);
