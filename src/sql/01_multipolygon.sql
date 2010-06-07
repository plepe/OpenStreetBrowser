drop aggregate if exists to_array(geometry);
CREATE AGGREGATE to_array (
BASETYPE = geometry,
SFUNC = array_append,
STYPE = geometry[],
INITCOND = '{}'); 

create or replace function join_to_multipolygon(geometry[])
returns geometry
as $$
declare
  outer_ways	geometry[];
  next          geometry[];
  this          geometry;
  ret_outer	geometry;
  o             int;
  o1            int;
  done		boolean:=true;
begin
  outer_ways:=$1;
  next:=Array[]::geometry[];

  if(outer_ways is null) then
    return null;
  end if;

  for o in array_lower(outer_ways, 1)..array_upper(outer_ways, 1) loop
    if(IsClosed(outer_ways[o])) then
      ret_outer:=ST_Collect(ret_outer, outer_ways[o]);
    else
      next:=array_append(next, outer_ways[o]);
    end if;
  end loop;

  outer_ways:=next;
  done:=true;

  if array_lower(outer_ways, 1) is null then
    return ret_outer;
  end if;

  while(done) loop
    done:=false;
    next:=Array[]::geometry[];

    for o in array_lower(outer_ways, 1)..array_upper(outer_ways, 1) loop
      this:=outer_ways[o];

      for o1 in o+1..array_upper(outer_ways, 1) loop

	if (this is not null) and (ST_Touches(this, outer_ways[o1])) then
	  this:=ST_LineMerge(ST_Collect(this, outer_ways[o1]));
	  outer_ways[o1]:=null;
	  done:=true;
	end if;
      end loop;

      if this is not null then
	next:=array_append(next, this);
      end if;

    end loop;

    outer_ways:=next;
  end loop;

  for o in array_lower(outer_ways, 1)..array_upper(outer_ways, 1) loop
    if(IsClosed(outer_ways[o])) then
      ret_outer:=ST_Collect(ret_outer, outer_ways[o]);
    end if;
  end loop;

  return ret_outer;
end;
$$ language 'plpgsql';

create or replace function build_multipolygon(geometry[], geometry[])
returns geometry
as $$
declare
  outer_ways	geometry;
  inner_ways	geometry;
begin
  outer_ways:=join_to_multipolygon($1);
  inner_ways:=join_to_multipolygon($2);

  if inner_ways is null then
    return outer_ways;
  end if;

  return ST_Difference(outer_ways, inner_ways);
end;
$$ language 'plpgsql';
