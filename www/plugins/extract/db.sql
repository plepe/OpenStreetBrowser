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


CREATE OR REPLACE FUNCTION extract_init() RETURNS boolean AS $$
DECLARE
  num_rows  int;
BEGIN
  raise notice '%', 'insert into osm_all_extract ( '
    || 'select * '
    || 'from ( '
    || '  select '
    || '    osm_id, '
    || '    classify_hmatch(osm_id, osm_tags, osm_way, Array[''extract'']) as osm_tags, '
    || '    osm_way '
    || '  from '
    || '    osm_all '
    || '  where '
    || classify_hmatch_sqlwhere('extract')
    || ') x '
    || 'where '
    || '  osm_tags ? ''#extract'');';

  GET DIAGNOSTICS num_rows = ROW_COUNT;
  raise notice 'inserted to osm_all_extract (%)', num_rows;

  return true;
END;
$$ language 'plpgsql';

select extract_init();
