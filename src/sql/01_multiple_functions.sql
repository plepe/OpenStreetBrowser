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

create or replace function parse_number(text)
  returns float
  as $$
declare
  val      alias for $1;
  val_n    text;
  val_u    text;
  numval   float;
  unit_fac float;
begin
  if val ~ E'^([0-9\.,]+) *(.*)$' then
    val_n:=substring(val from E'^([0-9\.,]+) *.*');
    val_u:=substring(val from E'^[0-9\.,]+ *(.*)');

    if val_n similar to E'^[0-9]+$' then
      numval=cast(val_n as float);
    elsif val_n similar to E'^[0-9]*\.[0-9]+$' then
      numval=cast(val_n as float);
    elsif val_n similar to E'^[0-9]*,[0-9]+$' then
      numval=cast(replace(val_n, ',', '.') as float);
    else
      return null;
    end if;

    if val_u in ('m', 'V', 'l') then
      unit_fac:=1;
    elsif val_u in ('km', 'kV') then
      unit_fac:=1000;
    elsif val_u in ('ha') then
      unit_fac:=100;
    elsif val_u in ('cm') then
      unit_fac:=0.01;
    elsif val_u in ('mm') then
      unit_fac:=0.001;
    else
      unit_fac:=1;
    end if;

    return numval*unit_fac;
  end if;

  return null;
end;
$$ language 'plpgsql';

create or replace function is_between(text, float, bool, float, bool)
  returns bool 
  as $$
declare
  val      alias for $1;
  numval   float;
  floor    alias for $2;
  ceil     alias for $4;
  floor_eq alias for $3;
  ceil_eq  alias for $5;
begin
  numval:=parse_number(val);

  if(numval is null) then
    return false;
  end if;

  if(floor is null) then
  elsif(floor_eq=true) then
    if numval<floor then
      return false;
    end if;
  else
    if numval<=floor then
      return false;
    end if;
  end if;

  if(ceil is null) then
  elsif(ceil_eq=true) then
    if numval>ceil then
      return false;
    end if;
  else
    if numval>=ceil then
      return false;
    end if;
  end if;

  return true;
end;
$$ language 'plpgsql';

create or replace function oneof_between(text[], int, bool, int, bool)
  returns bool
  as $$
declare
  arr alias for $1;
  i   int:=1;
begin
  for i in array_lower(arr, 1)..array_upper(arr, 1) loop
    if is_between(arr[i], $2, $3, $4, $5) then
      return true;
    end if;
  end loop;
  return false;
end;
$$ language 'plpgsql';
