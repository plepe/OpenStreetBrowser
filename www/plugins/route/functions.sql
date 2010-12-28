CREATE OR REPLACE FUNCTION route_dir(text[], text[]) RETURNS text AS $$
DECLARE
  refs alias for $1;
  roles alias for $2;
  i int;
  dir int;
BEGIN
  dir:=0;
  for i in array_lower(roles, 1)..array_upper(roles, 1) loop
    if roles[i] in ('', 'both') then
      dir:=dir|3;
    elsif substr(roles[i], 1, 7)='forward' then
      dir:=dir|1;
    elsif substr(roles[i], 1, 8)='backward' then
      dir:=dir|2;
    end if;
  end loop;

  return (Array['forward', 'backward', 'both'])[dir];
END;
$$ LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION route_refs(text[], text[]) RETURNS text AS $$
DECLARE
  refs alias for $1;
  roles alias for $2;
BEGIN
  return array_to_string(array_nat_sort(array_unique(refs)), ', ');
END;
$$ LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION route_importance(text, text[]) RETURNS text AS $$
DECLARE
  route alias for $1;
  network alias for $2;
BEGIN
  if route in ('tram', 'bus', 'trolley', 'trolleybus') then
    return 'suburban';
  elsif route in ('light_rail', 'subway') then
    return 'urban';
  elsif route in ('train', 'rail', 'railway', 'ferry') then
    return 'regional';
  elsif array_pos(network, 'e-road') is not null and route in ('road') then
    return 'international';
  elsif route in ('road') then
    return 'regional';
  elsif array_pos(network, 'icn') is not null or array_pos(network, 'iwn') is not null then
    return 'international';
  elsif array_pos(network, 'ncn') is not null or array_pos(network, 'nwn') is not null then
    return 'national';
  elsif array_pos(network, 'rcn') is not null or array_pos(network, 'rwn') is not null or route in ('hiking') then
    return 'regional';
  elsif array_pos(network, 'lcn') is not null or array_pos(network, 'lwn') is not null or array_pos(network, 'mtb') is not null or route in ('bicycle', 'mtb') then
    return 'suburban';
  end if;

  return 'local';
END;
$$ LANGUAGE plpgsql immutable;

