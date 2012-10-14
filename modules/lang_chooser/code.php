<?
function lang_chooser_main_links($list) {
  global $ui_lang;

  $list[]=array(5, "<a href='javascript:lang_chooser_show()'><img src='img/lang/{$ui_lang}.png'></a>");
}

register_hook("main_links", "lang_chooser_main_links");
