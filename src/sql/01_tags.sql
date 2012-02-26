create or replace function _tags_data(text)
returns hstore
as $$
declare
  x           text[];
  _osm_type   text;
  _osm_id     bigint;
  _osm_typeid alias for $1;
begin
  x:=string_to_array(_osm_typeid, '_');
  _osm_type:=x[1];
  _osm_id:=x[2];

  if (_osm_type='node') then
    return (select array_to_hstore(to_textarray(k), to_textarray(v)) from node_tags where "node_id"=_osm_id);
  elsif (_osm_type='way') then
    return (select array_to_hstore(to_textarray(k), to_textarray(v)) from way_tags where "way_id"=_osm_id);
  elsif (_osm_type='rel') then
    return (select array_to_hstore(to_textarray(k), to_textarray(v)) from relation_tags where "relation_id"=_osm_id);
  end if;
end;
$$ language 'plpgsql';

create or replace function tags_data(text)
returns hstore
as $$
declare
  _osm_id     alias for $1;
  ret         hstore[];
  x           text[];
begin
  x:=split_semicolon(_osm_id);
  for i in array_lower(x, 1)..array_upper(x, 1) loop
    ret[i]:=_tags_data(x[i]);
  end loop;

  return tags_merge(ret);
end;
$$ language 'plpgsql';

create or replace function tags_get(text, text)
returns text
as $$
declare
  _osm_id     alias for $1;
  _key	      alias for $2;
  ret         text[];
  x           text[];
begin
  x:=split_semicolon(_osm_id);
  for i in array_lower(x, 1)..array_upper(x, 1) loop
    ret[i]:=_tags_get(x[i], _key);
  end loop;

  ret:=array_unique(string_to_array(array_to_string(ret, ';'), ';'));
  return array_to_string(ret, ';');
end;
$$ language 'plpgsql';

create or replace function _tags_get(text, text)
returns text
as $$
declare
  x           text[];
  _osm_type   text;
  _osm_id     bigint;
  _osm_typeid alias for $1;
  _key	      alias for $2;
begin
  x:=string_to_array(_osm_typeid, '_');
  _osm_type:=x[1];
  _osm_id:=x[2];

  if (_osm_type='node') then
    return (select v from node_tags where "node_id"=_osm_id and "k"=_key);
  elsif (_osm_type='way') then
    return (select v from way_tags where "way_id"=_osm_id and "k"=_key);
  elsif (_osm_type='rel') then
    return (select v from relation_tags where "relation_id"=_osm_id and "k"=_key);
  end if;
end;
$$ language 'plpgsql';

create or replace function tags_get(text, text)
returns text
as $$
declare
  _osm_id     alias for $1;
  _key	      alias for $2;
  ret         text[];
  x           text[];
begin
  x:=split_semicolon(_osm_id);
  for i in array_lower(x, 1)..array_upper(x, 1) loop
    ret[i]:=_tags_get(x[i], _key);
  end loop;

  ret:=array_unique(string_to_array(array_to_string(ret, ';'), ';'));
  return array_to_string(ret, ';');
end;
$$ language 'plpgsql';


create or replace function tags_get(hstore, text)
returns text
as $$
declare
  osm_tags alias for $1;
  _key	   alias for $2;
begin
  return osm_tags->_key;
end;
$$ language 'plpgsql';

create or replace function tags_get(text[], text)
returns text
as $$
declare
  _osm_id   alias for $1;
  _key	    alias for $2;
  ret      text[];
  i         int;
begin
  for i in array_lower($1, 1)..array_upper($1, 1) loop
    ret[i]=tags_get(_osm_id[i], _key);
  end loop;

  ret:=array_unique(string_to_array(array_to_string(ret, ';'), ';'));
  return array_to_string(ret, ';');
end;
$$ language 'plpgsql';

create or replace function tags_parse(text, hstore, geometry, text)
returns text
as $$
declare
  _osm_id   alias for $1;
  _osm_tags alias for $2;
  _osm_way  alias for $3;
  pattern   alias for $4;
  patternex text[];
  patterni  int:=1;
  match_all boolean;
  ret       text;
  def       text;
  m         text;
  value     text;
begin
  if pattern is null or pattern='' then
    return '';
  end if;

  patternex:=string_to_array(pattern, ';');
  for i in array_lower(patternex, 1)..array_upper(patternex, 1) loop
    match_all:=true;
    ret:='';
    def:=patternex[i];

    while(def!='') loop
      m:=substring(def from E'^\\[([A-Za-z0-9_:]+)\\]');
      if(m is not null) then
	value=tags_get(_osm_tags, m);
	if(value is null) then
	  match_all:=false;
	end if;
	def:=substr(def, length(m)+3);
	ret:=ret || value;
      else
	ret:=ret || substr(def, 1, 1);
	def:=substr(def, 2);
      end if;
    end loop;

    if (match_all=true) then
      return ret;
    end if;
  end loop;

  return '';
end;
$$ language 'plpgsql';

create or replace function tags_parse(text, text)
returns text
as $$
declare
  _osm_id   alias for $1;
  pattern   alias for $2;
  tags      hstore;
begin
  tags=tags_data(_osm_id);
  return tags_parse(_osm_id, tags, null, pattern);
end;
$$ language 'plpgsql';

drop table if exists tags_parse_cache_table;
create table tags_parse_cache_table (
  osm_type	character(10)	not null,
  osm_id	bigint		not null,
  pattern	text		not null,
  result	text		null,
  primary key(osm_type, osm_id, pattern)
);

create or replace function tags_parse_cache(text, bigint, text)
returns text
as $$
declare
  _osm_type alias for $1;
  _osm_id   alias for $2;
  _pattern  alias for $3;
  ret       text;
begin
  if _pattern is null then
    return '';
  end if;

  ret=tags_parse(_osm_type, _osm_id, _pattern);

  delete from tags_parse_cache_table where "osm_type"=_osm_type and "osm_id"=_osm_id and "pattern"=_pattern;
  insert into tags_parse_cache_table values (_osm_type, _osm_id, _pattern, ret);
  return ret;
end;
$$ language 'plpgsql';

-- Merge an array of tags, e.g.
-- tags_merge({'a=>b, b=>c;d', 'c=>d, a=>f, b=>x'})
--   -> 'a=>b;f b=>c;d;x c=>d'
create or replace function tags_merge(hstore[])
returns hstore
as $$
declare
  src       alias for $1;
  collect   hstore;
  keys      text[];
  i         int;
  j         int;
  t         text;
begin
  if src is null then
    return null;
  end if;

  collect:=''::hstore;

  for i in array_lower(src, 1)..array_upper(src, 1) loop
    keys:=akeys(src[i]);
    if keys is not null and array_lower(keys, 1) is not null then
      for j in array_lower(keys, 1)..array_upper(keys, 1) loop
	t:=collect->keys[j];
	if(t is null) then
	  t:=src[i]->keys[j];
	else
	  t:=substring(t||';'||(src[i]->keys[j]), 0, 4096);
	end if;

	collect:=collect|| (keys[j]=>t);
      end loop;
    end if;
  end loop;

  keys:=akeys(collect);
  if array_lower(keys, 1) is null then
    return ''::hstore;
  end if;

  for j in array_lower(keys, 1)..array_upper(keys, 1) loop
      collect:=collect|| (keys[j]=>
        array_to_string(array_unique(split_semicolon(collect->keys[j])), ';'));
  end loop;

  return collect;
end;
$$ language 'plpgsql';

create or replace function tags_merge(hstore, hstore)
returns hstore
as $$
declare
  src1      alias for $1;
  src2      alias for $2;
begin
  return tags_merge(Array[src1, src2]);
end;
$$ language 'plpgsql';
