-- returns true if second argument is higher than first argument
CREATE OR REPLACE FUNCTION nat_sort_cmp (text, text)
returns bool
as $$
declare
  val_left	alias for $1;
  val_right 	alias for $2;
  l1 text;
  r1 text;
  l2 text;
  r2 text;
  ul1 text;
  ur1 text;
begin
  l1=substring(val_left  from '^([0-9]+).*$');
  r1=substring(val_right from '^([0-9]+).*$');
  l2=substring(val_left  from '^[0-9]+(.*)$');
  r2=substring(val_right from '^[0-9]+(.*)$');

  if l1 is null then
    l1:='';
  end if;

  if r1 is null then
    r1:='';
  end if;

  if l1!='' and r1!='' then
    if cast(l1 as int)<cast(r1 as int) then
      return true;
    end if;
    if l1=r1 then
      return nat_cmp(l2, r2);
    end if;
    if cast(l1 as int)>cast(r1 as int) then
      return false;
    end if;
  end if;

  if l1='' and r1!='' then
    return false;
  end if;

  if l1!='' and r1='' then
    return true;
  end if;

  l1=substring(val_left  from '^([^0-9]*)([0-9].*)?$');
  r1=substring(val_right from '^([^0-9]*)([0-9].*)?$');
  l2=substring(val_left  from '^[^0-9]+([0-9].*)$');
  r2=substring(val_right from '^[^0-9]+([0-9].*)$');
  ul1=upper(l1);
  ur1=upper(r1);

  if l1 is null then l1:=''; end if;
  if r1 is null then r1:=''; end if;
  if l2 is null then l2:=''; end if;
  if r2 is null then r2:=''; end if;

  if l1!='' and r1!='' then
    if ul1<ur1 then
      return true;
    end if;
    if ul1=ur1 then
      if (l1=r1) or (l2!='' and r2!='') then
	return nat_cmp(l2, r2);
      else
        return l1<l2;
      end if;
    end if;
    if ul1>ur1 then
      return false;
    end if;
  end if;

  if l1='' and r1!='' then
    return true;
  end if;

  if l1!='' and r1='' then
    return false;
  end if;

  return false;
end
$$ language 'plpgsql';
