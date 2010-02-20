<?
include "../conf.php";
include "inc/tags.php";
include "inc/categories.php";
include "../src/wiki_stuff.php";

$id=$_GET[id];
switch($_GET[todo]) {
  case "save":
    $error=category_save($id, file_get_contents("php://input"), $_GET);

    Header("Content-Type: text/xml; charset=UTF-8");
    print "<?xml version='1.0' encoding='UTF-8' ?".">\n";
    print "<result>\n";
    $version=category_version();

    if($error) {
      print "  <status version='$version'";
      foreach($error as $ek=>$ev) {
	print " $ek='$ev'";
      }
      print " />\n";
    }
    else {
      print "  <status version='$version' status='ok' />\n";
    }
    print "  <id>$id</id>\n";
    print "</result>\n";

    break;
  case "list":
    $list=category_list();

    Header("Content-Type: text/xml; charset=UTF-8");
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
    print $content;

    break;
  default:
    print "No valid 'todo'\n";
}
