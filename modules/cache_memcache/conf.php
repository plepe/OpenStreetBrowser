<?
$cache_memcache_active=true;

$cache_memcache_depend=array("db");

$cache_memcache_tags=new tags(array(
  "name"=>"DB Migration",
  "Author"=>"Stephan Plepelits",
));

$cache_memcache_provide="cache";
