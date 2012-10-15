<?
function keyshort_html_head() {
  global $root_path;

  if(!file_exists("$root_path/lib/jquery.hotkeys.js")) {
    print "Download <a href='https://github.com/tzuryby/jquery.hotkeys'>jquery.hotkeys.js</a> and put into the lib-directory!<br/>";
  }

  print "<script type='text/javascript' src='lib/jquery.hotkeys.js'></script>\n";
}

register_hook("html_head", "keyshort_html_head");

