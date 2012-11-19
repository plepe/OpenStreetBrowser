create or replace function _load_geo(text)
returns geometry 
as $$
declare
  _osm_type   text;
  _osm_id     bigint;
  _osm_typeid alias for $1;
  rel_type    text;
begin
  _osm_type:=substr(_osm_typeid, 1, 1);
  _osm_id  :=substr(_osm_typeid, 2);

  if(_osm_type='N') then
    return (select way from osm_point where id=_osm_typeid);
  elsif(_osm_type='W') then
    return (select way from osm_line where id=_osm_typeid
	    union
            select way from osm_polygon where id=_osm_typeid);
  elsif(_osm_type='R') then
    return (select way from osm_rel where id=_osm_typeid
	    union
            select way from osm_polygon where id=_osm_typeid);
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
