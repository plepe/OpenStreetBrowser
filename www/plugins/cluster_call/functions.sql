CREATE OR REPLACE FUNCTION cluster_call(text, text) RETURNS boolean AS $$
DECLARE
  _event	alias for $1;
  _parameters	alias for $2;
BEGIN
  insert into cluster_call values (now(), _event, _parameters);
  return true;
END;
$$ LANGUAGE plpgsql volatile;
