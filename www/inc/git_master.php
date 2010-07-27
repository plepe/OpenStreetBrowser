<?
// see bottom of this file for some documentation about this git-thingy

// A git_master is a database which is handled by git. 
// There are several directories (git_dir), e.g. for categories or icons, and
// each of them has objects (git_obj).
class git_master {
  var $path;
  var $dir_list;
  var $log="";
  var $commit_id=null;

  function __construct($path) {
    $this->path=$path;
    $this->dir_list=array();

    $this->check_state();
  }

  function path($path="") {
    return "{$this->path}/{$path}";
  }

  function register_dir($dir) {
    $this->dir_list[$dir->id]=$dir;
  }

  function get_dir($id) {
    return $this->dir_list[$id];
  }

  function chdir($path="") {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    $this->last_cwd=getcwd();
    chdir("{$this->path}/{$path}");
  }

  function chback() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    if($this->last_cwd)
      chdir($this->last_cwd);
  }

  function exec($command, $stdin=0, $path="") {
    $ret="";
    $this->log.="> $command\n";

    $descriptors=array(
      0=>array("pipe", "r"),
      1=>array("pipe", "w"),
      2=>array("pipe", "w"));

    $p=proc_open($command, $descriptors, $pipes, "{$this->path}/{$path}");
    if($stdin)
      fwrite($pipes[0], $stdin);
    $ret=stream_get_contents($pipes[1]);
    $error=stream_get_contents($pipes[2]);
    
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($p);

    $this->log.=$ret;
    $this->log.="stderr>$error";

    return $ret;
  }

  function lock() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    lock_dir("{$this->path}");
  }

  function unlock() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    unlock_dir("{$this->path}");
  }

  // check_state
  // checks whether database is in sane state
  //
  // parameters:
  // 
  // return:
  // true        oh yeah, everything alright
  // message     no, contains statement
  function check_state() {
    $this->is_sane=false;

    // $lists_dir set?
    if(!$this->path) {
      return "No path set!";
    }

    // No directory to change into ...
    if(!file_exists("{$this->path}")) {
      return "path '$this->path' does not exist!";
    }

    $this->is_sane=true;
    $this->chdir();

    // Check if git repository is ready
    if(!file_exists(".git")) {
      $this->exec("git init");
      $this->exec("git commit -m 'Init' --allow-empty");

      if(!file_exists("$this->path/.git")) {
	$this->is_sane=false;
	return "Could not create git repository!";
      }
    }

    $this->chback();

    return true;
  }

  // version()
  // Returns the current version
  function version($branch="", $nolock=0) {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    if(!$nolock) {
      $this->lock();
      $this->chdir();
    }

    $r=$this->exec("git log -n1 $branch");

    if(!$nolock) {
      $this->chback();
      $this->unlock();
    }

    if(!preg_match("/^commit ([a-z0-9]+)/", $r, $m)) {
      return "Couldn't get file version.";
    }

    return $m[1];
  }

  function commit_continue($commit_id) {
    $this->commit_id=$commit_id;
  }

  function commit_id() {
    return $this->commit_id;
  }

  // commit_start()
  // Call this function before changing anything in the database
  // Parameters:
  //   version ... the version of the file(s) before the change, e.g. when 
  //               they were requested by the editor
  //   branch  ... if we had conflicts when changing that file, supply 
  //               the branch id
  // Alternative Parameters:
  //   array('version'=>see above, 'branch'=>see above, ...)
  //
  // Return:
  //   branch  ... the branch we created to apply changes
  // Notes:
  //   - The database is locked, make sure to call commit_end() when finished!
  function commit_start($version=null, $branch=null) {
    global $current_user;

    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    $this->lock();
    $this->chdir();

    if(is_array($version)) {
      $branch=$version['branch'];
      $version=$version['version'];
    }

    if($branch) {
      $this->exec("git checkout $branch");
      $this->exec("git rebase $version");
    }
    else {
      $branch=uniqid();

      $this->exec("git branch $branch $version");
      $this->exec("git checkout $branch");
    }

    $author=$current_user->get_author();
    $this->exec("git commit --allow-empty -m 'temporary message' --author='$author'");
    $this->exec("git checkout master");

    $this->commit_id=$branch;
    $this->chback();
    $this->unlock();

    return $branch;
  }

  // commit_end()
  // call when finished changing all files
  // Parameters:
  //   message  ... The commit message
  // Return:
  //   0        ... on success
  //   branch   ... on failure
  function commit_end($message) {
    global $current_user;

    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    if(!$this->commit_id) {
      return array("status"=>"No commit started");
    }

    $author=$current_user->get_author();
    $branch=$this->commit_id;
    $this->chdir();
    $this->lock();

    $this->exec("git checkout $branch");
    $this->exec("git commit --allow-empty --amend -m '$message' --author='$author'");

    $branch_head=$this->version(null, 1);
    
    $changed_files=$this->exec("git log --name-only --pretty='format:#%H' master..HEAD");

    $p=$this->exec("git rebase master");
    $p=explode("\n", $p);
    $error=0;
    $conflict_files=array();
    foreach($p as $r) {
      if(preg_match("/^CONFLICT.*conflict in (.*)/", $r, $m)) {
	$error=1;
	$conflict_files[]=$m[1];
      }
    }

    if($error) {
      $this->exec("git rebase --abort");
      $this->exec("git checkout master");
      $error=array('status'=>"Merge failed",
                   'files'=>$conflict_files,
		   'version'=>$this->version("master"),
		   'branch'=>$this->commit_id,
		   'branch_head'=>$branch_head,
		  );
    }
    else {
      $this->exec("git checkout master");
      $this->exec("git merge $branch");
      $this->exec("git branch -d $branch");
      unset($this->commit_id);
    }

    $this->unlock();
    $this->chback();

    if(!$error) {
      $this->preprocess($changed_files);
    }

    return $error;
  }

  function commit_cancel() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    if(!$this->commit_id) {
      return array("status"=>"No commit started");
    }

    $this->lock();

    $this->exec("git branch -D {$this->commit_id}");

    $this->unlock();

    return 0;
  }

  function commit_open() {
    $this->lock();
    $this->chdir();

    $this->exec("git checkout {$this->commit_id}");
  }

  function commit_close() {
    global $current_user;

    $author=$current_user->get_author();
    $this->exec("git commit --allow-empty -m 'temporary message' --author='$author'");
    $this->exec("git checkout master");

    $this->unlock();
    $this->chback();
  }

  // accepts a string as returned by
  // git log --name-only --pretty='format:#%H' since..until
  function preprocess($changed_files) {
    $changed_files=explode("\n", $changed_files);
    $changed_list=array();
    foreach($changed_files as $f) {
      if(preg_match("/^([^\/]*)\/([^\/]*)\/(.*)$/", $f, $m)) {
	$changed_list[$m[1]][$m[2]]=$m[3];
      }
    }

    foreach($changed_list as $dir_id=>$list) {
      $git_dir=$this->get_dir($dir_id);
      $git_dir->preprocess($list);
    }
  }

  function preprocess_all() {
    foreach($this->dir_list as $id=>$dir) {
      $dir->preprocess_all();
    }
  }

  function create_file($dir, $id) {
    if(!$this->commit_id) {
      return array("status"=>"No commit started.");
    }

    if(!$id)
      $id=uniqid();

    $this->chdir();
    mkdir("$id/");

    $this->commit_open();

    $this->exec("git add $id/");

    $this->commit_close();

    $file=new $this->file_proto($this, $id, array());

    $this->chback();
    return $file;
  }

  function foo() {
    return "bar";
  }

  function get_file($id) {
    $this->commit_open();

    $r=$this->exec("git ls-files $id/");
    $r=explode("\n", $r);
    $list=array();
    foreach($r as $f) {
      if(preg_match("/^$id\/(.*)$/", $f, $m))
	$list[]=$m[1];
    }

    $file=new $this->file_proto($this, $id, $list);

    $this->commit_close();

    return $file;
  }

  function xml($xml) {
    $res=dom_create_append($xml, "result", $xml);

    $list=$this->file_list();
    foreach($list as $l) {
      $l->xml($res, $xml);
    }
  }

}

// workflow
// 
// A git directory applies this method of version control to a database.
// You have a directory "database/". For every file you have a directory,
// e.g. "a/" and "b/". This can for example be icons. In every directory
// you might have the source image, and xml-file with tags and derived images
// (png-files, files with changed color, smaller/larger icons, ...). The first
// two files are subject to version control, the other don't.
//
// $master is an instance of class git_master
//
// (1) when the user requests a file for editing, call
//
//   $dir=$master->get_dir($dir_id);
//   $commit_id=$master->commit_start();
//   $obj_a=$dir->get_obj("a");
//   $icon=$obj_a->load("file.svg");
// if you just want the file for displaying skip the commit_start()-line.
//
// (2) you can further save a changed version of the file:
//   $obj_a->save("file.svg", $content);
//
// (3) if you are finished editing, do:
//   $error=$master->commit_end("message what you changed");
//
// if error is !=0 we had an error merging that commit (which was created 
// on a branch) to the master branch. The error contains the id of the current
// master version:
//   $error=('status'=>"merge failed",
//           'files'=>array("dir_id/obj_id/file.svg"),
//           'version'=>'1a2b3c4...', 'branch'=>$commit_id, 
//           'branch_head'=>'2a3b4c5...' );
//
// (4) You have to create a resolve commit:
//   $obj_a->load("file.svg", $error['branch']) <- the file with conflicts
//   $obj_a1->load("file.svg", $error['version']) <- the file on master head
//   $obj_a2->load("file.svg", $error['branch_head']) <- the file on branch 
//                                                   head before the conflict
// Resolve the error and save as in (2) and (3)
//
// (5) if the commit is seperated to several calls to the php script, you have
// to remember the commit_id and every time call
//   $master->commit_continue($commit_id);
