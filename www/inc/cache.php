<?
function cache_search($osm_id, $k) {
  $pg_osm_id=postgre_escape($osm_id);
  $pg_k=postgre_escape($k);

  $res=sql_query("select cache_search({$pg_osm_id}, {$pg_k}) as content");
  if($elem=pg_fetch_assoc($res))
    return $elem['content'];

  return null;
}

function cache_insert($osm_id, $k, $content, $depend=array()) {
  $pg_osm_id=postgre_escape($osm_id);
  $pg_k=postgre_escape($k);
  $pg_content=postgre_escape($content);
  $pg_depend=array();
  foreach($depend as $depend_id)
    $pg_depend[]=postgre_escape($depend_id);
  $pg_depend="Array[".implode(",", $pg_depend)."]::text[]";

  sql_query("select cache_insert({$pg_osm_id}, {$pg_k}, {$pg_content}, {$pg_depend})");

  return $content;
}

function cache_remove($depend_id) {
  $pg_depend_id=postgre_escape($depend_id);

  sql_query("select cache_remove($pg_depend_id)");
}
