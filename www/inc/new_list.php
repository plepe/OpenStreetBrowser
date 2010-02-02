<?
include "/osm/skunkosm/x.php";

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

  html_export_var(array("category_list"=>$category_list));
}

register_hook("html_done", html_done_list);
