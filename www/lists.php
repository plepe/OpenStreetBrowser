<?
include "../conf.php";

$id=$_GET[id];
switch($_GET[todo]) {
  case "save":
    if($id=="new") {
      $id=uniqid("list_");
    }
    if(!$id) {
      print "No ID given!\n";
      exit;
    }

    $f=fopen("$lists_dir/$id.xml", "w");
    $postdata = file_get_contents("php://input");
    fprintf($f, $postdata);
    fclose($f);

    Header("Content-Type: text/xml; charset=UTF-8");
    print "<?xml version='1.0' encoding='UTF-8' ?>\n";
    print "<result>\n";
    print "  <status>Ok</status>\n";
    print "  <id>$id</id>\n";
    print "</result>\n";

    break;
  case "list":
    $ret="";
    $d=opendir("$lists_dir");
    while($f=readdir($d)) {
      if(preg_match("/^(.*)\.xml$/", $f, $m)) {
	$x=new DOMDocument();
	$x->loadXML(file_get_contents("$lists_dir/$f"));
	$name=$x->getElementsByTagName("name")->item(0)->nodeValue;
	$ret.="  <list id='$m[1]'>$name</list>\n";
      }
    }

    Header("Content-Type: text/xml; charset=UTF-8");
    print "<?xml version='1.0' encoding='UTF-8' ?>\n";
    print "<result>\n";
    print $ret;
    print "</result>\n";

    break;
  case "load":
    if(!file_exists("$lists_dir/$id.xml")) {
      print "File not found!\n";
      exit;
    }

    Header("Content-Type: text/xml; charset=UTF-8");
    print file_get_contents("$lists_dir/$id.xml");

    break;
  default:
    print "No valid 'todo'\n";
}
