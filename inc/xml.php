<?
function objects_to_xml($list, $xml, $osm, $all=0, $bounds=0) {
  $loaded=array();
  $to_load_list=array();

  call_hooks("modify_geometry", $list);
  if($list) foreach($list as $id) {
    $r=load_object($id);

    if($r) {
      $loaded[]=$r->id;
      $c=$r->get_xml($osm, $xml, 1, $bounds);
//      if($c)
//	$osm->appendChild($c);

//      if($all) {
//	$to_load_list=array_merge($to_load_list, $r->member_list());
//      }
    }
  }

//  $loaded=array();
//  do {
//    $has_loaded=0;

//    foreach($to_load_list as $obj) {
//      if(!in_array($obj, $loaded)) {
//	$r=load_element($obj);
//	$loaded[]=$r->long_id;
//
//	if($r) {
//	  $c=$r->get_xml($xml);
//	  if($c)
//	    $osm->appendChild($c);
//
//	  if($all) {
//	    $size_to_load_list=sizeof(array_unique($to_load_list));
//	    $to_load_list=array_unique(array_merge($to_load_list, $r->member_list()));
//	    if(sizeof($to_load_list)>$size_to_load_list)
//	      $has_loaded=1;
//	  }
//	}
//      }
//    }
//  } while($has_loaded);
/*
  $list=explode(",", $param[ways]);
  if($list) foreach($list as $id) {
    $r=new way($id);
    $c=$r->get_xml($xml);
    if($c)
      $osm->appendChild($c);
  }

  $list=explode(",", $param[nodes]);
  if($list) foreach($list as $id) {
    $r=new node($id);
    $c=$r->get_xml($xml);
    if($c)
      $osm->appendChild($c);
  }
  */
}

function ajax_get_data($param, $xml) {
  $osm=$xml->createElement("osm");
  $osm->setAttribute("generator", "PublicTransport OSM");
  $xml->appendChild($osm);

  $list=explode(",", $param[obj]);

  return elements_to_xml($list, $xml, $osm, $param[all]);
}
