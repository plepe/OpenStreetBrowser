-- changes supplied table into a partitioned table (e.g. xxx)
-- there'll be a table xxx_other containing all objects which do not fit into
-- any of the subtables
-- use partition_add_part(table_name, 'great', 'value>10') to add partitions
--
-- parameters:
-- 1. name of the table (text)
-- 2. options (hstore)
--      none yet
CREATE OR REPLACE FUNCTION partition_numid_init_table(in table_name text, in options hstore default ''::hstore) returns boolean as $$
#variable_conflict use_variable
DECLARE
  r record;
  index_def text[]=Array[]::text[];
BEGIN
  -- set default values
  options='type=>numid, id_count=>1000000, id_column=>id'||options;

  -- add table to the list of partition_tables
  insert into partition_tables values (table_name, Array[]::text[], Array[]::text[], options);

  -- create trigger for insert statement
  execute 'create or replace function partition_numid_insert_trigger_'||table_name||'() returns trigger as $f$ BEGIN perform partition_numid_on_insert('''||table_name||''', NEW); return null; END; $f$ language plpgsql;';
  execute 'create trigger partition_numid_insert_trigger_'||table_name||' before insert on '||table_name||' for each row execute procedure partition_numid_insert_trigger_'||table_name||'();';

  -- save list of current indexes
  for r in execute 'select * from pg_indexes where tablename='''||table_name||'''' loop
    index_def=array_append(index_def, r.indexdef);
  end loop;
  update partition_tables
    set indexes=index_def
    where partition_tables.table_name=table_name;

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
CREATE OR REPLACE FUNCTION partition_numid_on_insert(in table_name text, in NEW anyelement) returns boolean as $$
#variable_conflict use_variable
DECLARE
  part_id int8;
  table_def record;
  opt hstore;
  count int8;
BEGIN
  select * into table_def from partition_tables where partition_tables.table_name=table_name;
  opt:=table_def.options;
  count:=cast(opt->'id_count' as int8);

  execute 'select $1.'||(opt->'id_column') into part_id using NEW;
  part_id=part_id/count;

  -- first insert the data into the temporary table
  begin
    execute 'insert into '||table_name||'_'||part_id||' select $1.*' using NEW;
  exception when undefined_table then
    execute 'create table '||table_name||'_'||part_id||' ( '||
      'check ( '||(opt->'id_column')||' >= '||(part_id*count)||
      ' and '||(opt->'id_column')||' < '||((part_id+1)*count)||') '||
      ') inherits ('||table_name||');';

    perform partition_table_indexes(table_name, cast(part_id as text));

    raise notice 'partition_numid: created subtable %_%', table_name, part_id;
    execute 'insert into '||table_name||'_'||part_id||' select $1.*' using NEW;
  end;

  return true;
END;
$$ LANGUAGE plpgsql;

-- remove all traces of a table
create or replace function partition_numid_drop_table(in table_name text) returns boolean as $$
#variable_conflict use_variable
DECLARE
BEGIN
  execute 'drop table '||table_name||' cascade;';
  execute 'delete from partition_tables where table_name='||quote_nullable(table_name)||';';

  return true;
END;
$$ language plpgsql;

