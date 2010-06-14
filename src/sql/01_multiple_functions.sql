create or replace function oneof_is(text[], text)
  returns bool
  as $$
declare
  arr alias for $1;
  val alias for $2;
  i   int:=1;
begin
  if array_lower(arr, 1) is null then
    return false;
  end if;

  for i in array_lower(arr, 1)..array_upper(arr, 1) loop
    if arr[i]=val then
      return true;
    end if;
  end loop;
  return false;
end;
$$ language 'plpgsql';

drop table if exists dimension_units;
create table dimension_units (
  id		text	not null,
  factor	float	not null,
  description	text	null,
  primary key(id)
);

-- length
insert into dimension_units values ('m'   , 1    );
insert into dimension_units values ('mm'  , 0.001);
insert into dimension_units values ('cm'  , 0.011);
insert into dimension_units values ('km'  , 1000);
insert into dimension_units values ('in'  , 0.0254);
insert into dimension_units values ('inch', 0.0254);
insert into dimension_units values ('ft'  , 0.3048);
insert into dimension_units values ('feet', 0.3048);
insert into dimension_units values ('yd'  , 0.9144);
insert into dimension_units values ('yard', 0.9144);
insert into dimension_units values ('mile', 1609.344);
insert into dimension_units values ('miles',1609.344);
insert into dimension_units values ('league',4828.032);
insert into dimension_units values ('leagues',4828.032);

-- area
insert into dimension_units values ('acre', 4046.8564224);
insert into dimension_units values ('acres',4046.8564224);
insert into dimension_units values ('m2'  , 1);
insert into dimension_units values ('m^2' , 1);
insert into dimension_units values ('m²'  , 1);
insert into dimension_units values ('km2' , 1000000);
insert into dimension_units values ('km^2', 1000000);
insert into dimension_units values ('km²' , 1000000);
insert into dimension_units values ('a'   , 100);
insert into dimension_units values ('ha'  , 10000);

-- voltage
insert into dimension_units values ('V'   , 1);
insert into dimension_units values ('kV'  , 1000);

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

    unit_fac:=(select factor from dimension_units where id=val_u);
    if(unit_fac is null) then
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
$$ language 'plpgsql';

create or replace function split_semicolon(text)
  returns text[]
  as $$
declare
  str alias for $1;
begin
  return string_to_array(str, ';');
end;
$$ language 'plpgsql' immutable;
