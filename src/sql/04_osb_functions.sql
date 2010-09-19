create or replace function osb_empty_string(text, hstore, geometry, hstore, text)
returns text
as $$
declare
begin
  return '';
end;
$$ language 'plpgsql';

create or replace function osb_tags_parse(text, hstore, geometry, hstore, text)
returns text
as $$
declare
begin
  return tags_parse($1, $2, $3, $4->$5);
end;
$$ language 'plpgsql';
