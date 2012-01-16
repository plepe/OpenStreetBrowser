-- changes supplied table into a partitioned table (e.g. xxx)
-- there'll be a table xxx_other containing all objects which do not fit into
-- any of the subtables
-- use partition_add_part(table_name, 'great', 'value>10') to add partitions
--
-- parameters:
-- 1. name of the table (text)
-- 2. options (hstore)
--      none yet
CREATE OR REPLACE FUNCTION partition_init_table(in table_name text, in options hstore default ''::hstore) returns boolean as $$
#variable_conflict use_variable
DECLARE
  r record;
  index_def text[]=Array[]::text[];
BEGIN
  -- set default values
  options=''||options;

  -- add table to the list of partition_tables
  insert into partition_tables values (table_name, Array[]::text[], Array[]::text[], options);

  -- create trigger for insert statement
  execute 'create or replace function partition_insert_trigger_'||table_name||'() returns trigger as $f$ BEGIN perform partition_on_insert('''||table_name||''', NEW); return null; END; $f$ language plpgsql;';
  execute 'create trigger partition_insert_trigger_'||table_name||' before insert on '||table_name||' for each row execute procedure partition_insert_trigger_'||table_name||'();';

  -- create first inherited table
  execute 'create table '||table_name||'_other () inherits ('||table_name||');';
  -- create temporary table which is using while inserting
  execute 'create table '||table_name||'__tmp () inherits ('||table_name||');';

  -- create query function
  execute 'create or replace function '||table_name||'_query(in parts text[], in _where text default '''', in options hstore default ''''::hstore) returns setof '||table_name||' as $f$ declare r '||table_name||'%rowtype; sql text; begin sql:=partition_compile_query('''||table_name||''', parts, _where, options); return query execute sql; return; end; $f$ language plpgsql;';

  -- save list of current indexes
  for r in execute 'select * from pg_indexes where tablename='''||table_name||'''' loop
    index_def=array_append(index_def, r.indexdef);
  end loop;
  update partition_tables
    set indexes=index_def
    where partition_tables.table_name=table_name;
  perform partition_table_indexes(table_name, 'other');

  -- move current data from table to other-subtable
  execute 'insert into '||table_name||' (select * from only '||table_name||');';
  execute 'delete from only '||table_name||';';
  
  return true;
END;
$$ LANGUAGE plpgsql;

-- add part
CREATE OR REPLACE FUNCTION partition_add_part(in table_name text, in part_id text, in part_where text) returns boolean as $$
#variable_conflict use_variable
DECLARE
BEGIN
  update partition_tables set
    parts_id=array_append(parts_id, part_id),
    parts_where=array_append(parts_where, part_where)
  where partition_tables.table_name=table_name;

  execute 'create table '||table_name||'_'||part_id||' () inherits ('||table_name||');';

  -- fill subtable with fitting data
  execute 'insert into '||table_name||'_'||part_id||' (select * from '||table_name||'_query(null::text[], $f$'||part_where||'$f$));';
  -- delete fitting data from other-subtable
  execute 'delete from '||table_name||'_other where '||part_where||';';

  -- create indexes on table
  perform partition_table_indexes(table_name, part_id);

  return true;
END;
$$ LANGUAGE plpgsql;

-- called from the insert-trigger ... does the actual insert
CREATE OR REPLACE FUNCTION partition_on_insert(in table_name text, in NEW anyelement) returns boolean as $$
#variable_conflict use_variable
DECLARE
  way geometry;
  table_list int2[];
  i int2;
  part_id text;
  part_where text;
  done boolean:=false;
  table_def record;
  r record;
  c int;
BEGIN
  select * into table_def from partition_tables where partition_tables.table_name=table_name;

  -- first insert the data into the temporary table
  execute 'insert into '||table_name||'__tmp select $1.*' using NEW;

  -- process each parts and try to fit data into these table
  if array_lower(table_def.parts_id, 1) is not null then
  for i in array_lower(table_def.parts_id, 1)..array_upper(table_def.parts_id, 1) loop
    part_id=table_def.parts_id[i];
    part_where=table_def.parts_where[i];
    execute 'insert into '||table_name||'_'||part_id||' (select * from '||table_name||'__tmp where '||part_where||');';

    GET DIAGNOSTICS c = ROW_COUNT;
    if c>0 then
      -- raise notice 'inserted into %', part_id;
      done:=true;
    end if;
  end loop; end if;

  -- it didn't fit into any of these table ... insert to other
  if not done then
    execute 'insert into '||table_name||'_other (select * from '||table_name||'__tmp);';
  end if;

  -- keep the temporary table tidy
  execute 'delete from '||table_name||'__tmp;';

  return true;
END;
$$ LANGUAGE plpgsql;

-- compiles a query on a table as used by the XXX_query() function
create or replace function partition_compile_query(in table_name text, in parts text[], in _where text default '', in options hstore default ''::hstore) returns text as $$
#variable_conflict use_variable
DECLARE
  r record;
  table_def record;
  sql text;
  tables text[]=Array[]::text[];
BEGIN
  -- get parts for the request
  if parts is null then
    select * into table_def from partition_tables where partition_tables.table_name=table_name;
    parts=table_def.parts_id;
    parts=array_append(parts, 'other');
  end if;

  -- compile table names
  for i in array_lower(parts, 1)..array_upper(parts, 1) loop
    tables=array_append(tables, 'select * from '||table_name||'_'||parts[i]);
  end loop;

  -- join tables with union
  sql='select * from ('||array_to_string(tables, ' union ')||') a';

  -- if there's a where specified concatenate to query
  if _where!='' then
    sql=sql||' where '||_where;
  end if;

  -- raise notice 'sql: %', sql;
  return sql;
END;
$$ language plpgsql;

-- add indexes to all sub-tables
create or replace function partition_add_index(in table_name text, in index_def text) returns boolean as $$
#variable_conflict use_variable
DECLARE
  table_def record;
  r record;
  i int;
  parts text[];
BEGIN
  update partition_tables
    set indexes=array_append(indexes, index_def)
    where partition_tables.table_name=table_name;

  select * into table_def from partition_tables where partition_tables.table_name=table_name;
  parts=table_def.parts_id;
  parts=array_append(parts, 'other');

  for i in array_lower(parts, 1)..array_upper(parts, 1) loop
    perform partition_add_index_table(table_name, parts_id[i], index_def);
  end loop;

  return true;
END;
$$ language plpgsql;

-- create an index on a subtable
create or replace function partition_add_index_table(in table_name text, in part_id text, in index_def text) returns boolean as $$
#variable_conflict use_variable
DECLARE
BEGIN
  execute replace(index_def, table_name, table_name||'_'||part_id);

  return true;
END;
$$ language plpgsql;

-- create all indexes for a (new) table
create or replace function partition_table_indexes(in table_name text, in part_id text) returns boolean as $$
#variable_conflict use_variable
DECLARE
  table_def record;
  i int;
BEGIN
  select * into table_def from partition_tables where partition_tables.table_name=table_name;
  if table_def.indexes=Array[]::text[] then
    return true;
  end if;

  for i in array_lower(table_def.indexes, 1)..array_upper(table_def.indexes, 1) loop
    perform partition_add_index_table(table_name, part_id, table_def.indexes[i]);
  end loop;

  return true;
END;
$$ language plpgsql;

-- remove all traces of a table
create or replace function quadtree_drop_table(in table_name text) returns boolean as $$
#variable_conflict use_variable
DECLARE
BEGIN
  execute 'drop table '||table_name||' cascade;';
  execute 'delete from partition_tables where table_name='||quote_nullable(table_name)||';';

  return true;
END;
$$ language plpgsql;
