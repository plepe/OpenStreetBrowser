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

  $ret.="<div class='box_opened'>\n";
  $ret.="<a class='zoom' href='#'>".lang("info_back")."</a><br>\n";

  $ret.="<h1>".lang("search_results")."</h1>\n";
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
  $ret.="</ul>\n";

  $ret.="<a class='external' href='javascript:search_more()'>".lang("search_more")."</a><br>\n";
  $ret.="(".lang("search_nominatim")." <a href='http://nominatim.openstreetmap.org/'>Nominatim</a>)<br>\n";

  $ret.="</div>\n";

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

function search_menu_show($list) {
  $menu_list[]=array(-6,
    "<div id='search' class='search' style='position:absolute; top:143px;'>\n".
    "<form name='osb_search_form_name' id='osb_search_form' action='javascript:search()'>\n".
    "<input name='osb_search' id='search' style='border-color:#999999;' value='".lang('search_field')."' onFocus='search_focus(this)' onkeyup='search_brush(this,event)' onblur='search_onblur(this)' 'title='".lang('search_tip')."'/>\n".
    "<img name='brush' src='plugins/search/brush.png' border='0' alt='' title='".lang('search_clear')."' style='position:absolute; right:3px; top:6px; visibility:hidden; cursor:pointer;' onclick='search_clear(document.osb_search_form_name.osb_search)' onmousedown='if (event.preventDefault) event.preventDefault()'>\n".
    "</form></div>\n");
}

register_hook("menu_show", search_menu_show);
