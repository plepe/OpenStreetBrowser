<?
function search($param) {
  global $load_xml;
  $search_str=$param[value];
  $add_param=array();
  $ret="";

  $search_str=urlencode($search_str);
  $add_param[]="q=$search_str";

  if($param[shown])
    $add_param[]="exclude_place_ids=$param[shown]";

  if($param[viewbox])
    $add_param[]="viewbox=$param[viewbox]";

  $add_param[]="format=xml";

  $res=file_get_contents("http://nominatim.openstreetmap.org/search?".
                         implode("&", $add_param));
  $resdom=new DOMDocument();
  $resdom->loadXML($res);

  $ret.="<a class='zoom' href='javascript:list_reload()'>".lang("info_back")."</a><br>\n";
  $ret.="<b>Search results</b> (provided by <a href='http://nominatim.openstreetmap.org/'>Nominatim</a>):";
  if($param[shown])
    $ret.="<a nominatim_id='$param[shown]'></a>";
  $ret.="<ul>\n";
  $obl=$resdom->getElementsByTagname("place");
  for($i=0; $i<$obl->length; $i++) {
    $ob=$obl->item($i);

    $type=$ob->getAttribute("osm_type");
    if($type=="relation")
      $type="rel";
    $id=$ob->getAttribute("osm_id");

    $nominatim_id=$ob->getAttribute("place_id");

    $r=list_entry("{$type}_{$id}", $ob->getAttribute("display_name"));
    $r=strtr($r, array("<a href="=>"<a nominatim_id='$nominatim_id' href="));

    $ret.=$r;
  }
  $ret.="<a href='javascript:search_more()'>more results</a>";
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

