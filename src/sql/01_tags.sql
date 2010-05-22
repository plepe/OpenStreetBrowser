create or replace function tags_get(text, bigint, text)
returns text
as $$
declare
  _osm_type alias for $1;
  _osm_id   alias for $2;
  _key	   alias for $3;
begin
  if (_osm_type='node') then
    return (select v from node_tags where "node_id"=_osm_id and "k"=_key);
  elsif (_osm_type='way') then
    return (select v from way_tags where "way_id"=_osm_id and "k"=_key);
  elsif (_osm_type='rel') then
    return (select v from relation_tags where "relation_id"=_osm_id and "k"=_key);
  end if;
end;
$$ language 'plpgsql';

create or replace function tags_get(text[], bigint[], text)
returns text[]
as $$
declare
  _osm_type alias for $1;
  _osm_id   alias for $2;
  _key	    alias for $3;
  _ret      text[];
  i         int;
begin
  for i in array_lower($1, 1)..array_upper($1, 1) loop
    _ret[i]=tags_get(_osm_type[i], _osm_id[i], _key);
  end loop;

  return array_unique(_ret);
end;
$$ language 'plpgsql';

create or replace function tags_parse(text, bigint, text)
returns text
as $$
declare
  _osm_type alias for $1;
  _osm_id   alias for $2;
  pattern   alias for $3;
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
	value=tags_get(_osm_type, _osm_id, m);
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

create or replace function tags_parse(text[], bigint[], text)
returns text
as $$
declare
  _osm_type alias for $1;
  _osm_id   alias for $2;
  pattern   alias for $3;
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
	value=array_to_string(tags_get(_osm_type, _osm_id, m), ';'::text);
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

-- generate hstore from array with alternating key/value-members
create or replace function array_to_hstore(text[])
returns hstore
as $$
declare
  src       alias for $1;
  dst       hstore;
  i         int:=1;
begin
  dst:=''::hstore;

  while i<array_upper($1, 1) loop
    dst:=dst || (src[i]=>src[i+1]);
    i:=i+2;
  end loop;

  return dst;
end;
$$ language 'plpgsql';

-- merge to arrays, the first holding all keys, the second all values
create or replace function array_to_hstore(text[], text[])
returns hstore
as $$
declare
  src_k     alias for $1;
  src_v     alias for $2;
  dst       hstore;
  i         int:=1;
begin
  dst:=''::hstore;

  while i<=array_upper($1, 1) loop
    dst:=dst || (src_k[i]=>src_v[i]);
    i:=i+1;
  end loop;

  return dst;
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
  collect:=''::hstore;

  for i in array_lower(src, 1)..array_upper(src, 1) loop
    keys:=akeys(src[i]);
    for j in array_lower(keys, 1)..array_upper(keys, 1) loop
      t:=collect->keys[j];
      if(t is null) then
	t:=src[i]->keys[j];
      else
	t:=t||';'||(src[i]->keys[j]);
      end if;

      collect:=collect|| (keys[j]=>t);
    end loop;
  end loop;

  keys:=akeys(collect);
  for j in array_lower(keys, 1)..array_upper(keys, 1) loop
      collect:=collect|| (keys[j]=>
        array_to_string(array_unique(split_semicolon(collect->keys[j])), ';'));
  end loop;

  return collect;
end;
$$ language 'plpgsql';
