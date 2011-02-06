CREATE OR REPLACE FUNCTION classify_hmatch(text, hstore, geometry, text[]) RETURNS hstore AS $$
DECLARE
  id alias for $1;
  osm_tags hstore;
  way alias for $3;
  classify_type alias for $4;
  val text;
  ret hstore;
  i int;
BEGIN
  osm_tags:=$2;
  -- raise notice 'classify % (%)', id, classify_type;

  for i in array_lower(classify_type, 1)..array_upper(classify_type, 1) loop
    select "result" into ret from (
      (select
	"result", "importance"
      from
	classify_hmatch
      where
	"type"=classify_type[i] and
	osm_tags @> "match" and
	"key_exists" is null)
    union all
      (select
	"result", "importance"
      from
	classify_hmatch
      where
	"type"=classify_type[i] and
	osm_tags @> "match" and
	"key_exists" is not null and
	osm_tags ? "key_exists")
    ) as foo
    order by
      "importance" desc
    limit 1;

    if ret is not null then
      osm_tags := osm_tags || ret;
    end if;
  end loop;

  return osm_tags;
END;
$$ LANGUAGE plpgsql stable;
