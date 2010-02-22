<?
$load_xml=array();
require_once("../conf.php");
$sql=pg_connect("dbname=$db_name user=$db_user password=$db_passwd host=$db_host");
require_once("inc/list.php");

function ajax_load_list($bounds, $xml) {
  global $load_xml;

  if(!$bounds[items])
    $bounds[items]=array();

  $ret=call_all_infolists($bounds);

  //return array($ret, "route_list");
  $text=$xml->createTextNode($ret);
  $res=$xml->createElement("result");
  $value=$xml->createElement("text");
  $value->appendChild($text);
  $res->appendChild($value);
  $xml->appendChild($res);

  $osm=$xml->createElement("osm");
  $osm->setAttribute("generator", "PublicTransport OSM");
  $res->appendChild($osm);

  //objects_to_xml($load_xml, $xml, $osm, 1, $bounds);
}

function ajax_find_objects($param, $xml) {
  global $load_xml;

  $load_xml[]=$param[obj];

  $ret=find_objects($param);
  $text=$xml->createTextNode($ret);

  $ret=$xml->createElement("result");
  $value=$xml->createElement("text");
  $value->appendChild($text);
  $ret->appendChild($value);
  $xml->appendChild($ret);

  $osm=$xml->createElement("osm");
  $osm->setAttribute("generator", "PublicTransport OSM");
  $ret->appendChild($osm);

  objects_to_xml($load_xml, $xml, $osm, 1);

}

$prefered_zoom_levels=array();
function map_zoom($zoom) {
  global $prefered_zoom_levels;

  $prefered_zoom_levels[]=$zoom;
}

function ajax_details($param, $xml) {
  global $load_xml;
  global $prefered_zoom_levels;

  $load_xml[]=$param[obj];

  $ret=load_object($param[obj])->info($param);
  $text=$xml->createTextNode($ret);

  $ret=$xml->createElement("result");
  $value=$xml->createElement("text");
  $value->appendChild($text);
  $ret->appendChild($value);
  $xml->appendChild($ret);

  $value=$xml->createElement("zoom");
  $ret->appendChild($value);

  $center=load_object($param[obj])->place()->get_centre();
  $value->setAttribute("lon", $center[lon]);
  $value->setAttribute("lat", $center[lat]);

  if(sizeof($prefered_zoom_levels)) {
    sort($prefered_zoom_levels);
    $value->setAttribute("zoom", $prefered_zoom_levels[0]);
  }

  $osm=$xml->createElement("osm");
  $osm->setAttribute("generator", "PublicTransport OSM");
  $ret->appendChild($osm);

  objects_to_xml($load_xml, $xml, $osm, 1);

}

function ajax_load_object($param, $xml) {
  global $load_xml;
  //$ob=load_element($param[obj]);
  /*
  $obj=explode("_", $param[obj]);

  switch($obj[0]) {
    case "relation":
    case "rel":
    case "route":
      $ob=load_relation($obj[1]);
      //return route_info($obj[1]);
      break;
    case "node":
//      return stop_info($obj[1]);
      $ob=load_node($obj[1]);
      break;
  }
  */

  //foreach($param[obj] as $p)
  $load_xml=$param[obj];

  $osm=$xml->createElement("osm");
  $ret=$xml->createElement("result");
  $osm->setAttribute("generator", "PublicTransport OSM");
  $ret->appendChild($osm);
  $xml->appendChild($ret);

//  $load_xml[]=$param[obj];
  objects_to_xml($load_xml, $xml, $osm, 1);

  $req=$xml->createElement("request");
  $ret->appendChild($req);

  foreach($param[obj] as $p) {
    $r=$xml->createElement("obj");
    $r->setAttribute("id", $p);
    $req->appendChild($r);
  }
}

function ajax_get_mapkey($param, $xml) {
  $mapkey=new map_key();
  $ret=$mapkey->show_info($param);
  $text=$xml->createTextNode($ret);

  $ret=$xml->createElement("result");
  $value=$xml->createElement("text");
  $value->appendChild($text);
  $ret->appendChild($value);
  $xml->appendChild($ret);

  $value=$xml->createElement("zoom");
  $ret->appendChild($value);
  $value->setAttribute("value", $param[zoom]);

  if($param[overlays])
    foreach($param[overlays] as $p=>$dummy) {
      $value=$xml->createElement("overlay");
      $ret->appendChild($value);
      $value->setAttribute("value", $p);
    }

}
