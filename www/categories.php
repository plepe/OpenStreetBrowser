<?
include "../conf.php";
include "inc/lock.php";
include "inc/tags.php";
include "inc/categories.php";
include "../src/wiki_stuff.php";

$output=fopen("/tmp/git.log", "a");
function ob_receive($text) {
  global $output;

  fwrite($output, $text);
}

ob_start(ob_receive);

$id=$_GET[id];
switch($_GET[todo]) {
  case "save":
    $status=category_save($id, file_get_contents("php://input"), $_GET);

    Header("Content-Type: text/xml; charset=UTF-8");
    ob_end_clean();

    print "<?xml version='1.0' encoding='UTF-8' ?".">\n";
    print "<result>\n";
    $version=category_version();

    if($status[status]!==true) {
      print "  <status version='$version'";
      foreach($status as $ek=>$ev) {
	print " $ek='$ev'";
      }
      print " />\n";
    }
    else {
      print "  <status version='$version' status='ok' />\n";
    }
    print "  <id>$status[id]</id>\n";
    print "</result>\n";

    break;
  case "list":
    $list=category_list();

    Header("Content-Type: text/xml; charset=UTF-8");
    ob_end_clean();

    print "<?xml version='1.0' encoding='UTF-8' ?".">\n";
    print "<result>\n";
    foreach($list as $k=>$v) {
	print "  <list id='$k'>$v</list>\n";
    }
    print "</result>\n";

    break;
  case "load":
    $content=category_load($id, $_GET);

    Header("Content-Type: text/xml; charset=UTF-8");
    ob_end_clean();

    print $content;

    break;
  default:
    ob_end_clean();
    print "No valid 'todo'\n";
}
