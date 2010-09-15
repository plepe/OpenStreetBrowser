<?
function toolbox_menu_show($list) {
  $entry=
    "<div id='toolboxbuttons'>\n".
    "<table id='toolboxbuttons_table' cellspacing='0' style='border:0px; margin:0px 5px 0px 0px; padding:0px;'>\n".
    "	<tr></tr>\n".
    "</table>\n".
    "</div>\n".
    "<div id='toolbox_container'></div>\n";

  $list[]=array(-9, $entry);
}


register_hook("menu_show", toolbox_menu_show);
