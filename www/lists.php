<?
include "../conf.php";
include "inc/tags.php";
include "../src/wiki_stuff.php";

function category_version() {
  global $lists_dir;

  chdir($lists_dir);
  $p=popen("git log -n1", "r");
  $r=fgets($p);
  pclose($p);

  if(!preg_match("/^commit ([a-z0-9]+)/", $r, $m)) {
    print "Couldn't get file version.\n";
    exit;
  }
  return $m[1];
}

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

// Check if git repository is ready
if(!file_exists("$lists_dir/.git")) {
  chdir($lists_dir);
  system("git init");
  system("git commit --allow-empty");

  if(!file_exists("$lists_dir/.git")) {
    print "Could not create git repository!");
    exit;
  }
}

function category_new($id, $content, $param=array()) {
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

    lock_dir("$lists_dir");

    $file=new DOMDocument();
    $file->loadXML($content);

    $l=$file->getElementByTagName("list");
    for($i=0; $i<$l->length; $i++) {
      $version=$l->item($i)->getAttribute("version");
      $l->item($i)->removeAttribute("version");
    }

    chdir($lists_dir);
    $branch=uniqid();

    if($conflict_branch=$param[branch]) {
      $branch=uniqid();

      system("git branch $branch $param[version]");
      system("git checkout $branch");
      system("git merge $conflict_branch");

      $f=fopen("$lists_dir/$id.xml", "w");
      fprintf($f, $file->saveXML());
      fclose($f);

      system("git add $lists_dir/$id.xml");
      system("git commit -m 'Fix merge' --author='webuser <web@user>'");
      system("git branch -d $conflict_branch");
    }
    else {
      system("git branch $branch $version");
      system("git checkout $branch");

      $f=fopen("$lists_dir/$id.xml", "w");
      fprintf($f, $file->saveXML());
      fclose($f);

      system("git add $lists_dir/$id.xml");
      system("git commit -m 'update' --author='webuser <web@user>'");
    }

    system("git checkout master");
    $p=popen("git merge $branch", "r");
    $error=0;
    while($r=fgets($p)) {
      if(preg_match("/^CONFLICT /", $r)) {
	$error=1;
      }
    }
    pclose($p);

    if($error) {
      system("git reset --hard");
    }
    else {
      system("git branch -d $branch");
    }

    process_file("$lists_dir/$id.xml");

    unlock_dir("$lists_dir");

    if($error) {
      return array("status"=>"merge failed", "branch"=>$branch);
    }
    else {
      return 0;
    }
}

$id=$_GET[id];
switch($_GET[todo]) {
  case "save":
    $error=category_new($id, file_get_contents("php://input"), $_GET);

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
    $ret="";

    lock_dir($lists_dir);

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
    closedir($d);

    unlock_dir($lists_dir);

    Header("Content-Type: text/xml; charset=UTF-8");
    print "<?xml version='1.0' encoding='UTF-8' ?".">\n";
    print "<result>\n";
    print $ret;
    print "</result>\n";

    break;
  case "load":
    lock_dir($lists_dir);

    if($_GET[version]) {
      chdir($lists_dir);
      $p=popen("git show $_GET[version]:$id.xml");
      while($r=fgets($p)) {
	$content.=$r;
      }
      pclose($p);
    }
    else {
      if(!file_exists("$lists_dir/$id.xml")) {
	unlock_dir($lists_dir);
	print "File not found!\n";
	exit;
      }

      $content=file_get_contents("$lists_dir/$id.xml")
      $version=category_version();
    }

    $file=new DOMDocument();
    $file->loadXML($content);

    $l=$file->getElementByTagName("list");
    for($i=0; $i<$l->length; $i++) {
      $l->item($i)->setAttribute("version", $version);
    }

    unlock_dir($lists_dir);

    Header("Content-Type: text/xml; charset=UTF-8");
    print $file->saveXML();

    break;
  default:
    print "No valid 'todo'\n";
}
