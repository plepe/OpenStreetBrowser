CREATE OR REPLACE FUNCTION array_pos(text[], text)
RETURNS int
AS $$
declare
-- src   int[]=array_sort($1);
haystack   alias for $1;
needle     alias for $2;
i int:=1;
begin
  while i<=array_count(haystack) loop
    if haystack[i]=needle then
      return i;
    end if;
    i:=i+1;
  end loop;

  return null;
end
$$ language 'plpgsql';
