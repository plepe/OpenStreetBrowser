<?
/* Additional HTML Headers */
$add_html_headers=array();

/* add_html_header() - add additional html headers to page
 * @param str string full tag(s), e.g. "<meta foo=\"bar\">"
 */
function add_html_header($str) {
  global $add_html_headers;

  $add_html_headers[]=$str;
}

/* print_add_html_headers() - print all headers, will be called from index
 * resets list of additional html headers
 */
function print_add_html_headers() {
  global $add_html_headers;

  print "<!-- add_html_headers -->\n";
  print implode("\n", $add_html_headers);
  print "\n<!-- /add_html_headers -->\n";

  $add_html_headers=array();
}

$export_vars_todo=array();
require_once("hooks.php");
$dir=opendir("inc/");
while($r=readdir($dir)) {
  unset($ext);
  if(substr($r, 0, 1)!=".") {
    $ext=substr($r, strrpos($r, ".")+1);
  }

  switch($ext) {
    case "php":
      require_once "inc/$r";
      break;
    case "js":
      if(!in_array($r, array("hooks.js", "lang.js")))
	add_html_header("<script type='text/javascript' src='inc/$r'></script>");
      break;
    case "css":
      add_html_header("<link rel='stylesheet' type='text/css' href=\"inc/$r\">");
  }
}
closedir($dir);
unset($dir);

function html_var_to_js($v) {
  if(!isset($v))
    return "null";

  switch(gettype($v)) {
    case "integer":
    case "double":
      $ret=$v;
      break;
    case "boolean":
      $ret=$v?"true":"false";
      break;
    case "string":
      $ret="\"".implode("\\n", explode("\n", addslashes($v)))."\"";
      break;
    case "array":
      $ar_keys=array_keys($v);
      if(($ar_keys[0]=="0")&&($ar_keys[sizeof($ar_keys)-1]==(string)(sizeof($ar_keys)-1))) {
        $ret1=array();
        foreach($v as $k1=>$v1) {
          $ret1[]=html_var_to_js($v1);
        }
        $ret="new Array(".implode(", ", $ret1).")";
      }
      else {
        $ret1=array();
        foreach($v as $k1=>$v1) {
          $ret1[]="\"$k1\":".html_var_to_js($v1);
        }
        $ret="{ ".implode(", ", $ret1)." }";
      }
      break;
    default:
      print "<b>var_to_js</b>: Invalid type ".gettype($v)."<br />\n";
      $ret="";
  }

  return $ret;
}

function export_formated_text($key, $text) {
  $text=strtr($text, array(">"=>"&gt;", "<"=>"&lt;", "&"=>"&amp;"));

  $i=0;
  while($i+1024<strlen($text)) {
    $anz=1024;
    
    if(($a=strrpos(substr($text, $i, $anz), "\n"))!==false) {
      $anz=$a;
    }

    while(eregi("&[a-zA-Z0-9]*$", substr($text, $i, $anz))) {
      $anz--;
    }

    print "<$key>".substr($text, $i, $anz)."</$key>\n";
    $i+=$anz;
  }

  print "<$key>".substr($text, $i)."</$key>\n";
}

function html_export_var($vars) {
  global $request_type;
  global $finished_http_header;
  global $export_vars_todo;

  $export_vars_todo=array_merge($export_vars_todo, $vars);

}

function real_export() {
  global $export_vars_todo;

  /*
  if($request_type!="xml") {
    if(!$finished_http_header)
      return;
    */

    print "<script type='text/javascript'>\n<!--\n";
    foreach($export_vars_todo as $k=>$v) {
      print "var $k=".html_var_to_js($v).";\n";
    }
    print "//-->\n</script>\n";
    /*
  }
  else {
    foreach($export_vars_todo as $key=>$value) {
      //print "<$key>".html_var_to_js($value)."</$key>\n";
      export_formated_text($key, html_var_to_js($value));
    }
  }
  */
  $export_vars_todo=array();
}

plugins_init();
lang_init();
register_hook("html_done", "real_export");
