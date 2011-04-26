drop function if exists classify_hmatch(text, hstore, geometry, text[]);
CREATE OR REPLACE FUNCTION classify_hmatch(text[], text, hstore, geometry, hstore default ''::hstore) RETURNS hstore AS $$
DECLARE
  classify_type alias for $1;
  id alias for $2;
  osm_tags hstore;
  way alias for $4;
  osm_type alias for $5;
  val text;
  ret hstore;
  rec record;
  i int;
BEGIN
  osm_tags:=cast(cache_search(id, 'classify_hmatch|'||cast(classify_type as text)) as hstore);
  if osm_tags is not null then
    return osm_tags;
  end if;

  osm_tags:=$3;
  -- raise notice 'classify % (%)', id, classify_type;

  for i in array_lower(classify_type, 1)..array_upper(classify_type, 1) loop
    select
      "result" into ret
    from
      classify_hmatch
    where
      "type"=classify_type[i] and
      osm_tags @> "match" and
      (CASE
	WHEN "key_exists" is null THEN true
	ELSE osm_tags ? "key_exists"
      END) and
      osm_type @> "type_match"
    order by
      "importance" desc
    limit 1;

    if ret is not null then
      for rec in
	select
	  key => tags_parse(id, osm_tags, way, value) as el
	from
	  each(ret)
      loop
	osm_tags := osm_tags || rec.el;
      end loop;
    end if;
  end loop;

  perform cache_insert(id, 'classify_hmatch|'||cast(classify_type as text), cast(osm_tags as text), '6 hours');

  return osm_tags;
END;
$$ LANGUAGE plpgsql stable;

CREATE OR REPLACE FUNCTION classify_hmatch_sqlwhere(text) RETURNS text AS $$
DECLARE
  res record;
  ret text[]:=Array[]::text[];
  r   text;
  classify_type alias for $1;
BEGIN
  for res in select * from classify_hmatch where "type"=classify_type loop
    r:=E'(osm_tags @> E''' || regexp_replace(cast(res.match as text), E'''', E'''''') || E'''';
    if res.key_exists is not null then
      r:=r || E' and osm_tags ? E''' || regexp_replace(res.key_exists, E'''', E'''''') || E'''';
    end if;
    r:=r || ')';

    ret:=array_append(ret, r);
  end loop;

  return '(' || array_to_string(ret, ' or ') || ')';
END;
$$ LANGUAGE plpgsql stable;
