-- changes to supplied table into a quadtree
-- parameters:
-- 1. name of the table (text)
-- 2. options (hstore)
--      'size'=>size when to split a quadrant in byte (default: 256 MB)
--
-- the table needs to have a column 'way', a geometry column on which to 
-- decide which subtable(s) to insert.
-- TODO: configure column via options
CREATE OR REPLACE FUNCTION quadtree_init_table(in table_name text, in options hstore default ''::hstore) returns boolean as $$
#variable_conflict use_variable
DECLARE
BEGIN
  -- add table to the list of quadtree_tables
  insert into quadtree_tables values (table_name, options);

  -- create trigger for insert statement
  execute 'create or replace function quadtree_insert_trigger_'||table_name||'() returns trigger as $f$ BEGIN perform quadtree_on_insert('''||table_name||''', NEW); return null; END; $f$ language plpgsql;';
  execute 'create trigger quadtree_insert_trigger_'||table_name||' before insert on '||table_name||' for each row execute procedure quadtree_insert_trigger_'||table_name||'();';

  -- create first inherited table
  execute 'create table '||table_name||'_1 () inherits ('||table_name||');';

  -- create quadtree
  execute 'create table '||table_name||'_quadtree ( path text not null );';
  perform AddGeometryColumn(table_name||'_quadtree', 'boundary', 900913, 'POLYGON', 2);
  execute 'alter table '||table_name||'_quadtree add column table_id serial;';
  execute 'insert into '||table_name||'_quadtree values ('''', ST_MakeEnvelope(-20037508.34,-20037508.34,20037508.34,20037508.34, 900913));';
  execute 'create index '||table_name||'_quadtree_boundary on '||table_name||'_quadtree using gist(boundary);';

  -- function to extract way from a row
  execute 'create or replace function quadtree_get_way('||table_name||') returns geometry as $f$ select $1.way $f$ language sql;';

  return true;
END;
$$ LANGUAGE plpgsql;

-- get list of subtables where the given geometry is part of
create or replace function quadtree_get_table_list(in table_name text, in way geometry) returns int2[] as $$
declare
  ret record;
begin
  for ret in execute 'select array_agg(table_id) as c from '||
    table_name||'_quadtree where boundary && '''||cast(way as text)||''' and ST_Distance(boundary, '''||cast(way as text)||''')=0;' loop
  end loop;
  return ret.c;
end;
$$ language plpgsql;

-- called from the insert-trigger ... does the actual insert
CREATE OR REPLACE FUNCTION quadtree_on_insert(in table_name text, in NEW anyelement) returns boolean as $$
DECLARE
  way geometry;
  table_list int2[];
  i int2;
BEGIN
  way:=quadtree_get_way(NEW);
  table_list:=quadtree_get_table_list(table_name, way);

  for i in array_lower(table_list, 1)..array_upper(table_list, 1) loop
    execute 'insert into '||table_name||'_'||i||' select $1.*' using NEW;
  end loop;

  return true;
END;
$$ LANGUAGE plpgsql;
