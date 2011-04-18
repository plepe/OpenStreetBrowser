<?
// MAYBE deprecated function
function old_category_save($id, $content, $param=array()) {
  global $lists_dir;
  global $current_user;

  if(($state=category_check_state())!==true) {
    return array('status'=>$state);
  }

  if($id=="new") {
    $id=uniqid("cat_");
  }
  if(!$id) {
    return array("status"=>"No ID given");
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
    $author=$current_user->get_author();
    system("git commit -m 'Fix merge' --author='$author'");
  }
  else {
    $branch=uniqid();

    system("git branch $branch $version");
    system("git checkout $branch");

    $f=fopen("$lists_dir/$id.xml", "w");
    fprintf($f, $file->saveXML());
    fclose($f);

    system("git add $id.xml");
    $author=$current_user->get_author();
    system("git commit -m 'Change category $id' --author='$author'");
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

//  $cat=new category($id);
//  $cat->compile();
  global $fifo_path;
  if(file_exists($fifo_path)) {
    $f=fopen($fifo_path, "w");
    fputs($f, "compile $id\n");
    fclose($f);
  }

  if($error) {
    return array("status"=>"merge failed", "branch"=>$branch, "id"=>$id);
  }
  else {
    return array("status"=>true, "id"=>$id);
  }
}

function old_category_list($lang="en") {
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

      //if($tags->get("hide")!="yes") {
	$ret[$m[1]]=$tags;
      //}
    }
  }
  closedir($d);

  unlock_dir($lists_dir);

  return $ret;
}

function old_category_load($id, $param=array()) {
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

function old_category_history($id, $param=array()) {
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


