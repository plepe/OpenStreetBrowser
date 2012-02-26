CREATE OR REPLACE FUNCTION array_sort (ANYARRAY)
RETURNS ANYARRAY LANGUAGE SQL
AS $$
SELECT ARRAY(
  SELECT $1[s.i] AS "foo"
  FROM generate_series(array_lower($1,1), array_upper($1,1)) AS s(i)
  ORDER BY foo
);
$$;
