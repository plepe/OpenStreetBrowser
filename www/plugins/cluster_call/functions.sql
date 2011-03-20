CREATE OR REPLACE FUNCTION cluster_call(text, text) RETURNS boolean AS $$
DECLARE
  _event	alias for $1;
  _parameters	alias for $2;
BEGIN
  insert into cluster_call values (now(), _event, _parameters);
  return true;
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION cluster_call_register(text, text) RETURNS boolean AS $$
DECLARE
  _event alias for $1;
  _fun alias for $2;
BEGIN
  delete from cluster_call_registered where event=_event and fun=_fun;
  insert into cluster_call_registered values (_event, _fun);

  return true;
END;
$$ LANGUAGE plpgsql volatile;

CREATE OR REPLACE FUNCTION _cluster_call_local(text, timestamp, text) RETURNS void AS $$
DECLARE
  _parameters   text;
  _now          text;
  _event        text;
  var		text;
  entry		record;
  ret		text;
  i		int;
  params	text[];
BEGIN
  _parameters=quote_literal($1);
  _now=quote_literal(cast($2 as text));
  _event=quote_literal($3);

  for entry in select * from cluster_call_registered where event=$3 loop

    raise notice '%', entry.fun;
    execute 'select '||entry.fun||'(E'||_parameters||', E'||_now||', E'||_event||')';

  end loop;
END;
$$ LANGUAGE plpgsql volatile;
