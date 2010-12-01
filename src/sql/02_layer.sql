create or replace function parse_layer(hstore)
returns int as $$
declare
  osm_tags alias for $1;
  ret	float;
begin
  if(osm_tags is null or not osm_tags?'layer') then
    if osm_tags->'tunnel' in ('yes', 'true') then
      return -1;
    elsif osm_tags->'power' in ('line') then
      return 5;
    else
      return 0;
    end if;
  end if;

  ret:=parse_highest_number($1->'layer');

  if(ret is null) then
    return 0;
  elsif ret>5 then
    return 5;
  elsif ret<-5 then
    return -5;
  end if;

  return floor(ret);
end;
$$ language 'plpgsql' immutable;
  
create index osm_line_layer_way on osm_line using gist(osm_way, parse_layer(osm_tags));
create index osm_polygon_layer_way on osm_polygon using gist(osm_way, parse_layer(osm_tags));
