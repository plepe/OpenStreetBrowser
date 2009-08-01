drop aggregate if exists to_textarray(text);
CREATE AGGREGATE to_textarray (
BASETYPE = text,
SFUNC = array_append,
STYPE = text[],
INITCOND = '{}'); 

drop aggregate if exists to_intarray(int4);
CREATE AGGREGATE to_intarray (
BASETYPE = int4,
SFUNC = array_append,
STYPE = int4[],
INITCOND = '{}'); 

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

CREATE OR REPLACE FUNCTION array_sort (ANYARRAY)
RETURNS ANYARRAY LANGUAGE SQL
AS $$
SELECT ARRAY(
  SELECT $1[s.i] AS "foo"
  FROM generate_series(array_lower($1,1), array_upper($1,1)) AS s(i)
  ORDER BY foo
);
$$;

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

create function array_explode1(anyarray) returns setof anyelement as
'begin
for i in array_lower($1, 1) array_upper($1, 1) loop
return next $1[i];
end loop;
return;
end' language plpgsql strict immutable;
create function array_explode(anyarray) returns setof anyelement as
'select * from array_explode1($1)' language sql strict immutable;

create function array_text_explode(text[]) returns text as
'begin
for i in array_lower($1, 1) array_upper($1, 1) loop
return next $1[i];
end loop;
return;
end' language plpgsql strict immutable;

