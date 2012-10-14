CREATE OR REPLACE FUNCTION array_unique(text[])
RETURNS text[]
AS $$
declare
-- src   int[]=array_sort($1);
src   alias for $1;
src_i int:=1;
ret   text[];
ret_i int:=1;
found bool;
begin
  while src_i<=array_count(src) loop
    ret_i:=1;
    found:=false;

    if src[src_i] is null then
      found:=true;
    end if;

    while (ret_i<=array_count(ret)) and not found loop
      if src[src_i]=ret[ret_i] then
	found:=true;
      end if;
      ret_i:=ret_i+1;
    end loop;

    if found=false then
      ret=array_append(ret, src[src_i]);
    end if;

    src_i:=src_i+1;
  end loop;
  return ret;
end
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION array_unique(int[])
RETURNS int[]
AS $$
declare
-- src   int[]=array_sort($1);
arr alias for $1;
src   int[];
index int:=1;
ret   int[];
last  int:=0;
begin
src=array_sort(arr);
while src[index]>0
  loop
    if src[index]<>last then
      ret=array_append(ret, src[index]);
      last:=src[index];
    end if;
    index:=index+1;
  end loop;

return ret;
end;
$$ language 'plpgsql';
