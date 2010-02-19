<?
include "../conf.php";
include "inc/tags.php";
include "../src/wiki_stuff.php";

function process_element($node, $cat) {
  $src=array();
  $ret=array();
  global $columns;
  global $columns_all;
  global $req;
  global $importance_levels;

  $cur=$node->firstChild;
  while($cur) {
    if($cur->nodeName=="tag") {
      $src[$cur->getAttribute("k")]=$cur->getAttribute("v");
    }
    $cur=$cur->nextSibling;
  }

  $list_columns=array();
  $l=parse_wholekey($src[tag], &$list_columns);

  $r="'$src[description]||";
//  if(eregi("^\[\[(.*)\.svg\]\]", $src[icon], $m))
//    $src[icon]="[[$m[1].png]]";
  $r.="$src[icon]";
  $r1=array();
  foreach($list_columns as $key=>$values) {
    $r1[]="$key='||(CASE WHEN \"$key\" is null THEN '' ELSE \"$key\" END)||'";
  }
  $r.="||".implode(" ", $r1)."'";

  $prior=9;

  if(!$src[importance]) {
    $importance="local";
  }
  else if($src[importance]=="*") {
    $importance=array_keys($importance_levels);
  }
  else
    $importance=array($src[importance]);

  $tables=array("polygon", "point");
  if($src[tables]) {
    $tables=explode(";", $src[tables]);
  }

  foreach($tables as $t) {
    foreach($importance as $imp)
      if($l)
	$req[$cat][$imp][$t]['case'][$prior][]="WHEN $l THEN $r";
      else
	$req[$cat][$imp][$t]['case'][$prior][]=1;

    if($src[importance]=="*") {
      if(!$columns_all[$cat][$t])
	$columns_all[$cat][$t]=array();
      $columns_all[$cat][$t]=array_merge_recursive($columns_all[$cat][$t], $list_columns);
    }
    else {
      if(!$columns[$cat][$imp][$t])
	$columns[$cat][$imp][$t]=array();
      $columns[$cat][$imp][$t]=array_merge_recursive($columns[$cat][$imp][$t], $list_columns);
    }
  }
}

function process_list($node, $cat) {
  $cur=$node->firstChild;
  $data=array();

  while($cur) {
    if($cur->nodeName=="tag") {
      $data[$cur->getAttribute("k")]=$cur->getAttribute("v");
    }
    elseif($cur->nodeName=="element") {
      process_element($cur, $cat);
    }
    $cur=$cur->nextSibling;
  }
}

function postprocess() {
  global $req;
  global $columns;
  global $columns_all;

  $res=array();
  foreach($req as $category=>$d1) {
    foreach($d1 as $importance=>$d2) {
      foreach($d2 as $tables=>$d4) {
	$d3=$d4['case'];
	$d3_sort=array_keys($d3);
	sort($d3_sort);
	$ret="";
	foreach($d3_sort as $p) {
	  $sqlstr=$d3[$p];
	  $ret.=implode("\n", $sqlstr);
	}
	$res[$category][$importance][$tables]['case']=$ret;
      }
    }

    if($columns[$category]) {
      $cols=array_keys($columns[$category]);
      $ret1=array();
      foreach($columns[$category] as $importance=>$d2) {
	foreach($d2 as $tables=>$d3) {
	  foreach($d3 as $col=>$vals) {
	    $res[$category][$importance][$tables]['columns'][$col]=$vals;
	    // if all values are "positive" (no 'not null' and no 'not in (...)') 
	    // then we can make use of indices
	    $pos=true;
	    foreach($vals as $v) {
	      if((substr($v, 0, 1)=="!")||($v=="*")) {
		$pos=false;
	      }
	    }

	    if($pos)
	      $res[$category][$importance][$tables]['where'][]="\"$col\" in ('".implode("', '", $vals)."')";
	  }
	}
      }
    }

    if($columns_all[$category]) {
      $cols=array_keys($columns_all[$category]);
      $ret1=array();
      foreach($columns_all[$category] as $tables=>$d2) {
	foreach($list_importance as $importance) {
	  $res[$category][$importance][$tables]['where_imp']=array();
	  foreach($d2 as $col=>$vals) {
	    $res[$category][$importance][$tables]['columns'][$col]=$vals;
	    $res[$category][$importance][$tables]['where_imp'][]="\"$col\" in ('".implode("', '", $vals)."')";
	  }
	}
      }
    }
  }

  return $res;
}

function process_file($file) {
  $dom=new DOMDocument();

  $dom->loadXML(file_get_contents($file));
  $cur=$dom->firstChild;

  while($cur) {
    if($cur->nodeName=="list") {
      $data=process_list($cur, "root");
    }
    $cur=$cur->nextSibling;
  }

  $ret=postprocess();
  $f=fopen("$file.save", "w");
  fwrite($f, serialize($ret));
  fclose($f);
}

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

    if(!$lists_dir) {
      print "Variable \$lists_dir is not set!<br>\n");
      exit;
    }
    if(!file_exists("$lists_dir")) {
      mkdir("$lists_dir");
      if(!file_exists("$lists_dir")) {
	print "$lists_dir doesn't exist! Create and make sure it's writable by the webserver!");
	exit;
      }
    }
    if(!file_exists("$lists_dir/.git")) {
      chdir($lists_dir);
      system("git init");
      system("git commit --allow-empty");

      if(!file_exists("$lists_dir/.git")) {
        print "Could not create git repository!");
	exit;
      }
    }

    $f=fopen("$lists_dir/$id.xml", "w");
    $postdata = file_get_contents("php://input");
    fprintf($f, $postdata);
    fclose($f);

    process_file("$lists_dir/$id.xml");

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
	$tags=new tags();
	$tags->readDOM($x->firstChild);
	$ret.="  <list id='$m[1]'>".$tags->get("name")."</list>\n";
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
