CREATE OR REPLACE FUNCTION array_count(anyarray)
RETURNS int
AS $$
declare
arr alias for $1;
i int;
begin
  if(arr is null) then
    return 0;
  end if;
  return cast (substring(array_dims(arr), ':(.+)]$') as int);
end;
$$ language 'plpgsql';
