<?
function screen_adapt_menu_short($list) {
  $list[]=array(-5, "<a onClick='screen_adapt_show_menu()'>".lang("menu")."</a>");
}

register_hook("main_menu_short", "screen_adapt_menu_short");
