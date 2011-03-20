<?
$load_xml=array();
require_once("../conf.php");
pg_query("SET enable_seqscan='off'");
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

$prefered_zoom_levels=array();
function map_zoom($zoom) {
  global $prefered_zoom_levels;

  $prefered_zoom_levels[]=$zoom;
}

function ajax_details($param, $xml) {
  global $load_xml;
  global $prefered_zoom_levels;

  $load_xml[]=$param[obj];

  if(!($ob=load_object($param[obj]))) {
    $ret=$xml->createElement("result");
    $xml->appendChild($ret);

    $text=$xml->createElement("text");
    $ret->appendChild($text);

    $content=$xml->createTextNode(lang("help:no_object", $param[obj]));
    $text->appendChild($content);

    return;
  }

  $ret=$ob->info($param);
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
