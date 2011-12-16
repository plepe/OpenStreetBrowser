-- changes to supplied table into a quadtree
-- parameters:
-- 1. name of the table (text)
-- 2. options (hstore)
--      'size'=>size when to split a quadrant in byte (default: 256 MB)
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

  return true;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION quadtree_on_insert(in table_name text, in NEW anyelement) returns boolean as $$
DECLARE
BEGIN
  execute 'insert into '||table_name||'_1 select $1.*' using NEW;
  return true;
END;
$$ LANGUAGE plpgsql;
