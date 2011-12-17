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

  -- create query function
  execute 'create or replace function '||table_name||'_query(in boundary geometry, in _where text default '''', in options hstore default ''''::hstore) returns setof '||table_name||' as $f$ declare r '||table_name||'%rowtype; sql text; begin sql:=quadtree_compile_query('''||table_name||''', boundary, _where, options); for r in execute sql loop return next r; end loop; return; end; $f$ language plpgsql;';

  return true;
END;
$$ LANGUAGE plpgsql;

-- get list of subtables where the given geometry is part of
create or replace function quadtree_get_table_list(in table_name text, in way geometry) returns int2[] as $$
declare
  ret record;
begin
  if way is null then
    return null;
  end if;

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

  if table_list is null then
    return false;
  end if;

  for i in array_lower(table_list, 1)..array_upper(table_list, 1) loop
    execute 'insert into '||table_name||'_'||table_list[i]||' select $1.*' using NEW;
  end loop;

  perform quadtree_check_split(table_name, table_list);

  return true;
END;
$$ LANGUAGE plpgsql;

-- check if a subtable needs a split, and do the split right away.
CREATE OR REPLACE FUNCTION quadtree_check_split(in table_name text, in table_list int2[]) returns void as $$
#variable_conflict use_variable
DECLARE
  table_size int;
  table_def record;
  this record;
  new record;
  parts geometry[];
BEGIN
  select * into table_def from quadtree_tables where quadtree_tables.table_name=table_name;

  for i in array_lower(table_list, 1)..array_upper(table_list, 1) loop
    -- get size. if size too big, start a split.
    select pg_relation_size(table_name||'_'||table_list[i]) into table_size;
    if table_size>cast(table_def.options->'size' as int) then

      -- get table -> this
      execute 'select * from '||table_name||'_quadtree where table_id='||table_list[i]||';' into this;
      raise notice 'split table %_% - path: %', table_name, table_list[i], this.path;

      -- calculate parts
      parts=Array[
	ST_TransScale(this.boundary,
	  ST_Xmin(this.boundary), ST_Ymin(this.boundary), 0.5, 0.5),
	ST_TransScale(this.boundary,
	  ST_Xmin(this.boundary), ST_Ymax(this.boundary), 0.5, 0.5),
	ST_TransScale(this.boundary,
	  ST_Xmax(this.boundary), ST_Ymax(this.boundary), 0.5, 0.5),
	ST_TransScale(this.boundary,
	  ST_Xmax(this.boundary), ST_Ymin(this.boundary), 0.5, 0.5)
	];

      for i in 1..4 loop
	-- create entry in XXX_quadtree
	execute 'insert into '||table_name||'_quadtree values ('||
	  ''''||this.path||i||''', '||
	  ''''||cast(parts[i] as text)||''');';
	execute 'select * from '||table_name||'_quadtree where path='''||this.path||i||'''' into new;

        -- create new table
	execute 'create table '||table_name||'_'||new.table_id||' () inherits ('||table_name||');';
	raise notice 'part path=% table_id=%', new.path, new.table_id;

	-- insert all matching objects into new table
	execute 'insert into '||table_name||'_'||new.table_id||
	  ' (select * from '||table_name||'_'||this.table_id||' where '||
	  table_name||'_'||this.table_id||'.way && '''||cast(parts[i] as text)||''' and ST_Distance('||table_name||'_'||this.table_id||'.way, '''||cast(parts[i] as text)||''')=0)';
      end loop;

      -- empty old table and marked as droppable (boundary is null). don't
      -- drop right now, because this would block all selects on this table
      -- until transaction has finished
      execute 'delete from '||table_name||'_'||this.table_id||';';
      execute 'update '||table_name||'_quadtree set boundary=null where table_id='||this.table_id||';';

      raise notice 'finish split';
    end if;

  end loop;
END;
$$ LANGUAGE plpgsql;

-- compiles a query on a table as used by the XXX_query() function
create or replace function quadtree_compile_query(in table_name text, in boundary geometry, in _where text default '', in options hstore default ''::hstore) returns text as $$
#variable_conflict use_variable
DECLARE
  r record;
  sql text;
  tables text[]=Array[]::text[];
BEGIN
  -- get list of tables matching the boundary of the query
  for r in execute 'select * from '||table_name||'_quadtree where boundary && '||quote_nullable(cast(boundary as text))||';' loop
    tables=array_append(tables, 'select * from '||table_name||'_'||r.table_id);
  end loop;

  -- join tables with union
  sql='select * from ('||array_to_string(tables, ' union ')||') a';

  sql=sql||' where way && '||quote_nullable(cast(boundary as text));

  -- if there's a where specified concatenate to query
  if _where!='' then
    sql=sql||' and '||_where;
  end if;

  -- raise notice 'sql: %', sql;
  return sql;
END;
$$ language plpgsql;
