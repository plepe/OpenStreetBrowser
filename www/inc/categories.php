<?
$importance_levels=array("international", "national", "regional", "urban", "suburban", "local");
include "postgis.php";
include "inc/categories_sql.php";

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

function process_rule($node, $cat) {
  $src=array();
  $ret=array();
  global $columns;
  global $columns_all;
  global $req;
  global $importance_levels;
  global $postgis_tables;

  $tags=new tags();
  $tags->readDOM($node);
  $id=$node->getAttribute("id");

  $tables=$tags->get("tables");
  if($tables)
    $tables=explode(";", $tables);
  else
    $tables=array("point");

  if(!$importance=$tags->get("importance"))
    $importance="local";
  $inc_importance=false;
  if($importance=="*")
    $inc_importance=true;

  foreach($tables as $table) {
    if($postgis_tables[$table]) {
      $match=parse_match($tags->get("match"));

//      if($kind=$tags->get("kind")) {
//	$kind=explode(";", $kind);
//	$kind_ret=parse_kind($kind, $table);
//	$ret[$table]=array_merge_recursive($ret[$table], $kind_ret);
//      }
    }

    // for tables which include importance
    if($importance=="*") {
      foreach($importance_levels as $imp_lev) {
	//array_deep_copy($ret, $r); /// brrr php still gives a warning
	$ret[$imp_lev][$table]['match'][$id]=array("and",
	  array("is", "importance", "$imp_lev"),
	  $match);
      }
    }
    else
      $ret[$importance][$table]['match'][$id]=$match;
  }

  return $ret;
}

function process_list($node, $cat) {
  $cur=$node->firstChild;
  $ret=array();

  while($cur) {
    if($cur->nodeName=="rule") {
      $r=process_rule($cur, $cat);
      
      $ret=array_merge_recursive($ret, $r);
    }
    $cur=$cur->nextSibling;
  }

  foreach($ret as $importance=>$x) {
    foreach($x as $table=>$rules) {
      $ret[$importance][$table]['sql']=build_sql_match_table($rules['match'], $table);
    }
  }

  return $ret;
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
    if($cur->nodeName=="category") {
      $data=process_list($cur, "root");
    }
    $cur=$cur->nextSibling;
  }

  $f=fopen("$file.save", "w");
  fwrite($f, serialize($data));
  fclose($f);
}

// category_check_state
// checks whether category-database is in sane state
//
// parameters:
// 
// return:
// true        oh yeah, everything alright
// message     no, contains statement
function category_check_state() {
  global $lists_dir;

  // $lists_dir set?
  if(!$lists_dir) {
    return "Variable \$lists_dir is not set!";
  }

  // No directory to change into ...
  if(!file_exists("$lists_dir")) {
    return "$lists_dir does not exist!";
  }

  // Check if git repository is ready
  if(!file_exists("$lists_dir/.git")) {
    chdir($lists_dir);
    system("git init");
    system("git commit -m 'Init' --allow-empty");

    if(!file_exists("$lists_dir/.git")) {
      return "Could not create git repository!";
    }
  }

  return true;
}

// function category_save()
// saves a category and processes it
//
// parameters:
// $id       the id of the file, could be 'new'
// $content  the content of the file
// $param    additional params
//    branch    the branch of a conflicting merge
//    version   the version of the file last loaded
//
// return:
//   array(
//     'status'=>'status message',  // boolean true if success
//     'branch'=>'id of conflicting branch'
//     'id'=>    'id of file'
//   )
function category_save($id, $content, $param=array()) {
  global $lists_dir;

  if(($state=category_check_state())!==true) {
    return array('status'=>$state);
  }

  if($id=="new") {
    $id=uniqid("cat_");
  }
  if(!$id) {
    print "No ID given!\n";
    exit;
  }

  if(!file_exists("$lists_dir")) {
    mkdir("$lists_dir");
    if(!file_exists("$lists_dir")) {
      print "$lists_dir doesn't exist! Create and make sure it's writable by the webserver!";
      exit;
    }
  }

  lock_dir("$lists_dir");

  $file=new DOMDocument();
  if(!($file->loadXML($content))) {
    unlock_dir($lists_dir);
    return array("status"=>"Could not load data");
  }

  $l=$file->getElementsByTagName("category");
  for($i=0; $i<$l->length; $i++) {
    $version=$l->item($i)->getAttribute("version");
    $l->item($i)->removeAttribute("version");
  }

  chdir($lists_dir);
  if($branch=$param[branch]) {
    system("git checkout $branch");
    system("git rebase $param[version]");

    $f=fopen("$lists_dir/$id.xml", "w");
    fprintf($f, $file->saveXML());
    fclose($f);

    system("git add $id.xml");
    system("git commit -m 'Fix merge' --author='webuser <web@user>'");
  }
  else {
    $branch=uniqid();

    system("git branch $branch $version");
    system("git checkout $branch");

    $f=fopen("$lists_dir/$id.xml", "w");
    fprintf($f, $file->saveXML());
    fclose($f);

    system("git add $id.xml");
    system("git commit -m 'Change category $id' --author='webuser <web@user>'");
  }

  $p=popen("git rebase master", "r");
  $error=0;
  while($r=fgets($p)) {
    if(preg_match("/^CONFLICT /", $r)) {
      $error=1;
    }
  }
  pclose($p);

  if($error) {
    system("git rebase --abort");
    system("git checkout master");
  }
  else {
    system("git checkout master");
    system("git merge $branch");
    system("git branch -d $branch");
  }

  unlock_dir("$lists_dir");

  process_file("$lists_dir/$id.xml");

  if($error) {
    return array("status"=>"merge failed", "branch"=>$branch, "id"=>$id);
  }
  else {
    return array("status"=>true, "id"=>$id);
  }
}

// category_list()
// lists all categories
//
// parameters:
// $lang      language
//
// return:
// array('category_id'=>'name', ...)
function category_list($lang="en") {
  global $lists_dir;

  if($state=category_check_state()!==true) {
    return array('status'=>$state);
  }

  $ret=array();
  lock_dir($lists_dir);

  $d=opendir("$lists_dir");
  while($f=readdir($d)) {
    if(preg_match("/^(.*)\.xml$/", $f, $m)) {
      $x=new DOMDocument();
      $x->loadXML(file_get_contents("$lists_dir/$f"));
      $tags=new tags();
      $tags->readDOM($x->firstChild);
      $ret[$m[1]]=$tags->get("name:$lang");
    }
  }
  closedir($d);

  unlock_dir($lists_dir);

  return $ret;
}

// category_load()
// returns content of a category
//
// parameters:
// $id     the if of the category
// $param  optional parameters
//   version    a certain version of that file
//
// return:
// content as text
// array('status')  on error
function category_load($id, $param=array()) {
  global $lists_dir;

  if($state=category_check_state()!==true) {
    return array('status'=>$state);
  }

  lock_dir($lists_dir);

  if($param[branch]) {
    chdir($lists_dir);
    system("git checkout $param[branch]");
    system("git rebase $param[version]");

    $content=file_get_contents("$lists_dir/$id.xml");

    $version=category_version();
    return $content;
  }
  elseif($param[version]) {
    chdir($lists_dir);
    $p=popen("git show $param[version]:$id.xml", "r");
    while($r=fgets($p)) {
      $content.=$r;
    }
    pclose($p);
    $version=$param[version];
  }
  else {
    if(!file_exists("$lists_dir/$id.xml")) {
      unlock_dir($lists_dir);
      print "File $id not found!\n";
      exit;
    }

    $content=file_get_contents("$lists_dir/$id.xml");
    $version=category_version();
  }

  $file=new DOMDocument();
  $file->loadXML($content);

  $l=$file->getElementsByTagName("category");
  for($i=0; $i<$l->length; $i++) {
    $l->item($i)->setAttribute("version", $version);
  }

  unlock_dir($lists_dir);
  return $file->saveXML();
}

// category_history()
// returns history of a category
//
// parameters:
// $id     the id of the category
// $param  optional parameters
//
// return:
// array('status')
function category_history($id, $param=array()) {
  global $lists_dir;
  $ret=array();

  if($state=category_check_state()!==true) {
    return array('status'=>$state);
  }

  chdir($lists_dir);

  $p=popen("git log --pretty=medium $id.xml", "r");
  while($r=fgets($p)) {
    $r=trim($r);
    if(preg_match("/^commit (.*)$/", $r, $m)) {
      $ret[]=array("version"=>$m[1]);
    }
    elseif(preg_match("/^([A-Za-z0-9]+):\h(.*)$/", $r, $m)) {
      $ret[sizeof($ret)-1][$m[1]]=$m[2];
    }
  }
  pclose($p);

  return $ret;
}
