<?
function recent_changes_main_links($list) {
  $list[]=array(0, "<a href='javascript:recent_changes_show()'>".lang("recent_changes:name")."</a>");
}

register_hook("main_links", "recent_changes_main_links");
