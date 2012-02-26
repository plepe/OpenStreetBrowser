create or replace function split_semicolon(text)
  returns text[]
  as $$
declare
  str alias for $1;
begin
  return string_to_array(str, ';');
end;
$$ language 'plpgsql' immutable;
