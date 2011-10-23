<?
function lang_chooser_main_links($list) {
  global $ui_lang;

  $list[]=array(5, "<img src='img/lang/{$ui_lang}.png'>");
}

register_hook("main_links", "lang_chooser_main_links");
