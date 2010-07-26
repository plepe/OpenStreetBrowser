<?
require_once("inc/git_directory.php");
$icon_dir;

class icon_file extends git_file {
}

function icon_init() {
  global $icon_dir;

  $icon_dir=new git_directory("icons", "icon_file");
}

register_hook("html_start", "icon_init");
register_hook("ajax_start", "icon_init");
