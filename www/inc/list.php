<?
require_once("inc/hooks.php");

$category_list=array(); //unserialize(file_get_contents("/osm/skunkosm/category_list.save"));
$overlays=unserialize(file_get_contents("/osm/skunkosm/overlays.save"));

function list_template() {
  global $category_list;
  global $lang_str;
  $ret="";

  $ret.="$lang_str[list_info]<br>\n";
  $ret.="<img src='img/ajax_loader.gif' /> loading</div>\n";

  return $ret;
}

function html_done_list() {
  global $category_list;
  global $overlays;

  html_export_var(array("category_list"=>$category_list, "overlays"=>$overlays));
}

register_hook("html_done", html_done_list);
