-- generate hstore from array with alternating key/value-members
create or replace function array_to_hstore(text[])
returns hstore
as $$
declare
  src       alias for $1;
  dst       hstore;
  i         int:=1;
begin
  dst:=''::hstore;

  while i<array_upper($1, 1) loop
    dst:=dst || (src[i]=>src[i+1]);
    i:=i+2;
  end loop;

  return dst;
end;
$$ language 'plpgsql';

-- merge to arrays, the first holding all keys, the second all values
create or replace function array_to_hstore(text[], text[])
returns hstore
as $$
declare
  src_k     alias for $1;
  src_v     alias for $2;
  dst       hstore;
  i         int:=1;
begin
  dst:=''::hstore;

  while i<=array_upper($1, 1) loop
    dst:=dst || (src_k[i]=>src_v[i]);
    i:=i+1;
  end loop;

  return dst;
end;
$$ language 'plpgsql';
