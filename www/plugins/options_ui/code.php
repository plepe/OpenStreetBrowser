<?
function options_ui_main_menu($list) {
  $list[]=
    array(0, "<a href='javascript:show_options()'>".lang("main:options")."</a>");
}

register_hook("main_links", "options_ui_main_menu");
