<?
global $geom_funs;
$geom_funs=array();

function ajax_geom($param) {
  global $geom_funs;

  if(!array_key_exists($param['fun'], $geom_funs)) {
    return false;
  }

  $id=postgre_escape($param['id']);
  $fun="geom_{$param['fun']}";
  $sql_param=array_to_hstore($param['param']);
  $context=array(
    "zoom"=>$param['zoom'],
    "scale_denominator"=>279541132.014/pow(2, ($param['zoom']-1)),
  );
  $context=array_to_hstore($context);

  $res=sql_query("select (x.ob).id as id, (x.ob).tags as tags, astext((x.ob).way) as way from $fun((select geo_object(osm_id, osm_tags, osm_way) from osm_all where osm_id=$id), $sql_param, $context) x");
  $elem=pg_fetch_assoc($res);

  $elem['tags']=parse_hstore($elem['tags']);

  return $elem;
}

// all 'geom_*' functions need to be registered:
// call 
// geom_register("buffer", array("radius"=>array("float", "100"), "foo"=>array("int"), "debug"=>array("bool")))
// -> registering function 'buffer' (called 'geom_buffer')
// -> available parameters: 'radius' of type float with default value 100
//                          'foo' of type int, no default value
//                          'debug' of type boolean
function geom_register($fun, $param) {
  global $geom_funs;

  $geom_funs[$fun]=$param;
}

function geom_init() {
  global $geom_funs;

  html_export_var(array("geom_funs"=>$geom_funs));
}

register_hook("init", "geom_init");
