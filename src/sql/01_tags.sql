create or replace function tags_get(text, int, text)
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

create or replace function tags_parse(text, int, text)
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
  patternex:=string_to_array(pattern, ';');
  for i in array_lower(patternex, 1)..array_upper(patternex, 1) loop
    match_all:=true;
    ret:='';
    def:=patternex[i];

    while(def!='') loop
      m:=substring(def from E'^\\[([A-Za-z0-9_:]+)\\]');
	raise notice 'm = %', m;
      if(m is not null) then
	value=tags_get(_osm_type, _osm_id, m);
	if(value is null) then
	  match_all:=false;
	end if;
	def:=substr(def, length(m)+3);
	ret:=ret || value;
	raise notice 'def = %', def;
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
