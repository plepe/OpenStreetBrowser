<?
function blog_main_links($list) {
  $list[]=array(1, "<a href='javascript:blog_show_menu()'>".lang("blog:name")."</a>");
}

register_hook("main_links", "blog_main_links");
