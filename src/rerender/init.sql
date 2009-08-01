create language plpgsql;

drop table osb_access;
create table osb_access (
  overlay	text	not null,
  x		int4	not null,
  y		int4	not null,
  zoom		int4	not null,
  month		int4	not null,
  count		int4	not null default 0,
  primary key(overlay, x, y, zoom, month)
);

create or replace function inc_access(overlay1 text, x1 int4, y1 int4, zoom1 int4, month1 int4) RETURNS VOID AS
$$
BEGIN
--  begin
    update osb_access set count=count+1 where "x"=x1 and "y"=y1 and "zoom"=zoom1 and "month"=month1 and "overlay"=overlay1;
    if found then
      return;
    end if;

    insert into osb_access values(overlay1, x1, y1, zoom1, month1, 1);
--    return;
--  exception when unique_violation then
--  end;
end;
$$
language plpgsql;
