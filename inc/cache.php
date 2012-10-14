<?
// Cache functions //
// cache_insert($id, $k, $v[, $depend][, $outdate])
//   inserts a key/value pair into the cache
//   - $id is the string representation of an object (e.g. 'rel_1234'). If this
//     object already exists in the cache, it will be updated
//   - $k is the key (a string)
//   - $v is a value and will be handled as string
//   - $depend is an array of depending objects, e.g. a the geometric
//     representation of a way depends on the way and it's nodes. You don't
//     have to add the id of the object to the depend-array.
//   - $outdate specifies an interval after which the content will be
//     deprecated. Specify the interval as pgsql interval, 
//     e.g. '5 days 3 hours'. Defaults to '1 month'.
//   - returns the value
// Example: cache_insert('rel_1234', 'foo', 'bar', Array['node_1'], '1 hour');
// 
// cache_search($id, $k)
//   returns a cached value for this object, if no value exists 'null' is
//   returned
// Example: cache_search('rel_1234', 'foo') -> 'bar'
//
// cache_remove($id)
//   deletes all values which belong to this object or depend on it
// Example: cache_remove('node_1')
//
// cache_clean()
//   deletes all outdated entries

function cache_search($osm_id, $k) {
  $pg_osm_id=postgre_escape($osm_id);
  $pg_k=postgre_escape($k);

  $res=sql_query("select cache_search({$pg_osm_id}, {$pg_k}) as content");
  if($elem=pg_fetch_assoc($res))
    return $elem['content'];

  return null;
}

function cache_insert($osm_id, $k, $content, $depend=array(), $interval=null) {
  $pg_osm_id=postgre_escape($osm_id);
  $pg_k=postgre_escape($k);
  $pg_content=postgre_escape($content);

  if(is_string($depend)) {
    $interval=$depend;
    $depend=array();
  }

  if(sizeof($depend)) {
    $pg_depend=array();
    foreach($depend as $depend_id)
      $pg_depend[]=postgre_escape($depend_id);
    $pg_depend="Array[".implode(",", $pg_depend)."]::text[]";
  }
  else {
    $pg_depend="null::text[]";
  }

  if($interval)
    $pg_interval=postgre_escape($interval);
  else
    $pg_interval="null";

  sql_query("select cache_insert({$pg_osm_id}, {$pg_k}, {$pg_content}, {$pg_depend}, {$pg_interval}::interval)");

  return $content;
}

function cache_remove($depend_id) {
  $pg_depend_id=postgre_escape($depend_id);

  sql_query("select cache_remove($pg_depend_id)");
}

function cache_clean() {
  sql_query("select cache_clean()");
}
