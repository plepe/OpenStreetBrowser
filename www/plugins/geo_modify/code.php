<?
global $geo_modify_funs;
$geo_modify_funs=array("buffer", "get_center", "grid");

function ajax_geo_modify($param) {
  global $geo_modify_funs;

  if(!in_array($param['fun'], $geo_modify_funs)) {
    return false;
  }

  $id=postgre_escape($param['id']);
  $fun="geo_modify_{$param['fun']}";
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
