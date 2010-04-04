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

