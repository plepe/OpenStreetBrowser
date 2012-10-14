create or replace function array_toint(anyarray)
returns int[]
as $$
declare
  arr alias for $1;
  dest int[];
  i int:=1;
begin
  if arr is null then
    return null;
  end if;
  for i in array_lower(arr, 1)..array_upper(arr, 1) loop
    if arr[i] similar to '^[0-9]+$' then
      dest[i]=cast (arr[i] as int);
    end if;
  end loop;
  return dest;
end;
$$ language 'plpgsql';
