CREATE OR REPLACE FUNCTION importance_value(text) RETURNS int AS $$
DECLARE
  v int;
BEGIN
  select value into v from importance where text=$1;

  if v is null then
    return 0;
  end if;

  return v;
END;
$$ LANGUAGE plpgsql immutable;

-- TODO: name clash: importance_text defined in render_route!!!
-- render_route should use this function(s)
CREATE OR REPLACE FUNCTION importance_text_new(int) RETURNS text AS $$
DECLARE
  v int;
  t text;
BEGIN
  v=round(($1)/10)*10+5;

  select text into t from importance where value=v;

  if t is null then
    if v<=0 then
      return 'local';
    else
      return 'global';
    end if;
  end if;

  return t;
END;
$$ LANGUAGE plpgsql immutable;
