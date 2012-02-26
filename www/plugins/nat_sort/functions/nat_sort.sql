CREATE OR REPLACE FUNCTION nat_sort (text[])
RETURNS text[]
AS $$
declare
src     alias for $1;
list    text[];
i int:=1;
j int:=1;
h text;
begin
  list=src;
  while i<=array_count(list) loop
    j:=1;
    while j<i loop
      if nat_cmp(list[i], list[j]) then
        h:=list[i];
	list[i]=list[j];
	list[j]=h;
      end if;
      j:=j+1;
    end loop;
    i:=i+1;
  end loop;

  return list;
end
$$ language 'plpgsql';


