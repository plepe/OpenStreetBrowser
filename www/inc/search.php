<?
$nominatim_ids=array();

function search($param) {
  global $nominatim_ids;
  global $load_xml;
  $search_str=$param[value];

  $res=file_get_contents("http://nominatim.openstreetmap.org/search?q=$search_str&format=xml");
  $resdom=new DOMDocument();
  $resdom->loadXML($res);

  $ret="<b>Search results</b> (provided by <a href='http://nominatim.openstreetmap.org/'>Nominatim</a>):<ul>\n";
  $obl=$resdom->getElementsByTagname("place");
  for($i=0; $i<$obl->length; $i++) {
    $ob=$obl->item($i);

    $type=$ob->getAttribute("osm_type");
    if($type=="relation")
      $type="rel";
    $id=$ob->getAttribute("osm_id");

    $nominatim_ids["{$type}_{$id}"]=$ob->getAttribute("place_id");

    $ret.=list_entry("{$type}_{$id}", $ob->getAttribute("display_name"));
  }
  $ret.="</ul>\n";

  return $ret;
}

function ajax_search($param, $xml) {
  global $load_xml;

  $result=$xml->createElement("result");
  $text=$xml->createTextNode(search($param));

  $xml->appendChild($result);
  $result->appendChild($text);

  $osm=$xml->createElement("osm");
  $osm->setAttribute("generator", "Nominatim");
  $result->appendChild($osm);

  objects_to_xml($load_xml, $xml, $osm, 1, $bounds);
}

