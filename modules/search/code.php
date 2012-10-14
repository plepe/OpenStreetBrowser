<?
function search($param) {
  $ret=array();
  $search_str=$param['value'];
  $add_param=array();

  $search_str=urlencode($search_str);
  $add_param[]="q=$search_str";

  if($param['shown'])
    $add_param[]="exclude_place_ids={$param['shown']}";

  if($param['viewbox'])
    $add_param[]="viewbox={$param['viewbox']}";

  $add_param[]="format=xml";

  $res=file_get_contents("http://nominatim.openstreetmap.org/search?".
                         implode("&", $add_param));

  $resdom=new DOMDocument();
  $resdom->loadXML($res);

  $obl=$resdom->getElementsByTagname("place");
  for($i=0; $i<$obl->length; $i++) {
    $ob=$obl->item($i);

    $type=$ob->getAttribute("osm_type");
    if($type=="relation")
      $type="rel";
    $id=$ob->getAttribute("osm_id");

    $nominatim_id=$ob->getAttribute("place_id");

    $ret_ob=array(
      'osm_id'   =>"{$type}_{$id}",
      'osm_tags' =>array(),
    );

    $ret_ob['osm_tags']['name']=$ob->getAttribute("display_name");
    $ret_ob['osm_tags']['nominatim_id']=$nominatim_id;
    $ret_ob['osm_tags']['lat']=$ob->getAttribute("lat");
    $ret_ob['osm_tags']['lon']=$ob->getAttribute("lon");

    $ret[]=$ret_ob;
  }

  return $ret;
}

function ajax_search($param, $xml) {
  global $load_xml;

  return search($param);
}

function search_menu_show($list) {
  $menu_list[]=array(-6,
    "<div id='search' class='search' style='position:absolute; top:143px;'>\n".
    "<form name='osb_search_form_name' id='osb_search_form' action='javascript:search()'>\n".
    "<input name='osb_search' id='search' style='border-color:#999999;' value='".lang('search_field')."' onFocus='search_focus(this)' onkeyup='search_brush(this,event)' onblur='search_onblur(this)' 'title='".lang('search_tip')."'/>\n".
    "<img name='brush' src='".modulekit_file("search", "brush.png")."' border='0' alt='' title='".lang('search_clear')."' style='position:absolute; right:3px; top:6px; visibility:hidden; cursor:pointer;' onclick='search_clear(document.osb_search_form_name.osb_search)' onmousedown='if (event.preventDefault) event.preventDefault()'>\n".
    "</form></div>\n");
}

register_hook("menu_show", search_menu_show);
