create or replace function oneof_is(text[], text)
  returns bool
  as $$
declare
  arr alias for $1;
  val alias for $2;
  i   int:=1;
begin
  for i in array_lower(arr, 1)..array_upper(arr, 1) loop
    if arr[i]=val then
      return true;
    end if;
  end loop;
  return false;
end;
$$ language 'plpgsql';
