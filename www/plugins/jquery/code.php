<?
function jquery_html_head() {
  global $root_path;

  if(!file_exists("$root_path/www/lib/jquery.min.js")) {
    print "Download <a href='http://jquery.com/download/'>jquery.min.js</a> and put into the lib-directory!<br/>";
  }

  print "<script type='text/javascript' src='lib/jquery.min.js'></script>\n";
}

register_hook("html_head", "jquery_html_head");
