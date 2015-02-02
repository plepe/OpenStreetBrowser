<?php
function permalink_menu(&$list) {
  $list[] = array(10, "<a href='' id='permalink'>". lang('main:permalink') ."</a>");
};

register_hook("main_links", "permalink_menu");
register_hook("main_menu_short", "permalink_menu");
