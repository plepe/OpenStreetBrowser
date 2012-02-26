create or replace function parse_number(text)
  returns float
  as $$
declare
  val      alias for $1;
  val_     text;
  val_n    text;
  val_u    text;
  numval   float;
  unit_fac float;
begin
  val_:=trim(both E'\t\n\r ' from val);

  if val_ ~ E'^([+-]?[0-9\.,]+)\\s*(.*)$' then
    val_n:=substring(val_ from E'^([+-]?[0-9\.,]+)\\s*.*');
    val_u:=substring(val_ from E'^[+-]?[0-9\.,]+\\s*(.*)');

    if val_n ~ E'^[+-]?[0-9]+$' then
      numval=cast(val_n as float);
    elsif val_n ~ E'^[+-]?[0-9]*\\.[0-9]+$' then
      numval=cast(val_n as float);
    elsif val_n ~ E'^[+-]?[0-9]*,[0-9]+$' then
      numval=cast(replace(val_n, ',', '.') as float);
    else
      return null;
    end if;

    unit_fac:=(select factor from dimension_units where id=val_u);
    if(unit_fac is null) then
      unit_fac:=1;
    end if;

    return numval*unit_fac;
  end if;

  return null;
end;
$$ language 'plpgsql' immutable;

create or replace function parse_number_or_0(text)
  returns float
  as $$
declare
  ret	float;
begin
  ret:=parse_number($1);
  
  if(ret is null) then
    return 0;
  else
    return ret;
  end if;
end;
$$ language 'plpgsql' immutable;

create or replace function parse_numbers(text)
  returns float[]
  as $$
declare
  val      alias for $1;
  val_list text[];
  ret_list float[];
  highest  float;
begin
  val_list:=split_semicolon(val);

  if array_lower(val_list, 1) is null then
    return null;
  end if;

  for i in array_lower(val_list, 1)..array_upper(val_list, 1) loop
    ret_list[i]:=parse_number(val_list[i]);
  end loop;

  return ret_list;
end;
$$ language 'plpgsql' immutable;

create or replace function parse_highest_number(text)
  returns float
  as $$
declare
  val      alias for $1;
  val_list text[];
  ret      float;
  highest  float;
  init     float;
begin
  val_list:=split_semicolon(val);
  init:=-999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999;
  highest:=init;

  if array_lower(val_list, 1) is null then
    return null;
  end if;

  for i in array_lower(val_list, 1)..array_upper(val_list, 1) loop
    ret:=parse_number(val_list[i]);
    if ret>highest then
      highest:=ret;
    end if;
  end loop;

  if(highest=init) then
    return null;
  end if;

  return highest;
end;
$$ language 'plpgsql' immutable;

create or replace function parse_lowest_number(text)
  returns float
  as $$
declare
  val      alias for $1;
  val_list text[];
  ret      float;
  init     float;
  lowest   float;
begin
  val_list:=split_semicolon(val);
  init:=999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999;
  lowest:=init;

  if array_lower(val_list, 1) is null then
    return null;
  end if;

  for i in array_lower(val_list, 1)..array_upper(val_list, 1) loop
    ret:=parse_number(val_list[i]);
    if ret<lowest then
      lowest:=ret;
    end if;
  end loop;

  if(lowest=init) then
    return null;
  end if;

  return lowest;
end;
$$ language 'plpgsql' immutable;

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
$$ language 'plpgsql' immutable;

create or replace function oneof_between(text[], float, bool, float, bool)
  returns bool
  as $$
declare
  arr alias for $1;
  i   int:=1;
begin
  if array_lower(arr, 1) is null then
    return false;
  end if;

  for i in array_lower(arr, 1)..array_upper(arr, 1) loop
    if is_between(arr[i], $2, $3, $4, $5) then
      return true;
    end if;
  end loop;
  return false;
end;
$$ language 'plpgsql' immutable;

create or replace function oneof_in(text[], text[])
  returns bool
  as $$
declare
  haystack alias for $1;
  needles  alias for $2;
  i   int:=1;
begin
  if array_lower(haystack, 1) is null then
    return false;
  end if;

  if array_lower(needles, 1) is null then
    return false;
  end if;

  for i in array_lower(needles, 1)..array_upper(needles, 1) loop
    if oneof_is(haystack, needles[i]) then
      return true;
    end if;
  end loop;
  return false;
end;
$$ language 'plpgsql' immutable;
