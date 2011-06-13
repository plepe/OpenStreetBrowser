<?
function ajax_my_maps_save($param, $xml, $post_data) {
  global $db_central;
  $data=json_decode($post_data, true);
  $id=postgre_escape($param['id']);
  $sql="begin;";
 
  $sql.="delete from my_maps_map where id=$id;";
  $sql.="delete from my_maps_item where map_id=$id;";

  $sql.="insert into my_maps_map values ($id, ".
        array_to_hstore($data['data']).");";
  foreach($data['items'] as $i=>$item) {
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

  return json_encode($ret);
}
