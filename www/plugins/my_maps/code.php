<?
function ajax_my_maps_save($param, $xml, $post_data) {
  global $db_central;
  $id=postgre_escape($param['id']);
  $sql="begin;";
 
  $sql.="delete from my_maps_map where id=$id;";
  $sql.="delete from my_maps_item where map_id=$id;";

  $sql.="insert into my_maps_map values ($id, ".
        array_to_hstore($post_data['data']).");";
  foreach($post_data['items'] as $i=>$item) {
    $geo=postgre_escape($item['geo']);
    unset($item['geo']);
    $i=postgre_escape($i);
    $sql.="insert into my_maps_item values ($id, $i, ".
          array_to_hstore($item).
          ", GeomFromText($geo, 900913));";
  }

  $sql.="commit;";

  sql_query($sql, $db_central);

  return true;
}

function ajax_my_maps_load($param, $xml) {
  $ret=array('data'=>array(), 'items'=>array());
  $id=postgre_escape($param['id']);

  $res=sql_query("select * from my_maps_map where id=$id");
  $elem=pg_fetch_assoc($res);
  $ret['data']=parse_hstore($elem['tags']);

  $res=sql_query("select \"tags\", astext(way) as \"way\" from my_maps_item where map_id=$id");
  while($elem=pg_fetch_assoc($res)) {
    $d=parse_hstore($elem['tags']);
    $d['geo']=$elem['way'];
    $ret['items'][]=$d;
  }

  return $ret;
}

function ajax_my_maps_list($param, $xml) {
  $ret=array();

  $res=sql_query("select * from my_maps_map");
  while($elem=pg_fetch_assoc($res)) {
    $d=parse_hstore($elem['tags']);
    $d['id']=$elem['id'];

    $ret[]=$d;
  }

  return $ret;
}
