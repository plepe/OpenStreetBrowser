create or replace function _load_geo(text)
returns geometry 
as $$
declare
  x           text[];
  _osm_type   text;
  _osm_id     bigint;
  _osm_typeid alias for $1;
  rel_type    text;
begin
  x:=string_to_array(_osm_typeid, '_');
  _osm_type:=x[1];
  _osm_id:=x[2];

  if(_osm_type='node') then
    return (select osm_way from osm_point where osm_id=_osm_typeid);
  elsif(_osm_type='way') then
    return (select osm_way from osm_line where osm_id=_osm_typeid
	    union
            select osm_way from osm_polygon where osm_id=_osm_typeid);
  elsif(_osm_type='rel') then
    return (select osm_way from osm_rel where osm_id=_osm_typeid
	    union
            select osm_way from osm_polygon where osm_id=_osm_typeid);
  end if;

  return null;
end;
$$ language 'plpgsql';

create or replace function load_geo(text)
returns geometry 
as $$
declare
  _osm_id   alias for $1;
  x         text[];
  ret       geometry[];
  tmp       geometry;
begin
  x:=split_semicolon(_osm_id);
  for i in array_lower(x, 1)..array_upper(x, 1) loop
    tmp=_load_geo(x[i]);
    if tmp is not null then
      ret[i]:=tmp;
    end if;
  end loop;

  return ST_Collect(ret);
end;
$$ language 'plpgsql';
