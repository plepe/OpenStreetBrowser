<?
function ajax_recent_changes_load($param) {
  $list=array();

  call_hooks("recent_changes_load", &$list, $param);

  return $list;
}

function recent_changes_main_links($list) {
  $list[]=array(0, "<a href='javascript:recent_changes_show()'>".lang("recent_changes:name")."</a>");
}

register_hook("main_links", "recent_changes_main_links");
