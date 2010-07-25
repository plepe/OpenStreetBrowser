<?
// workflow
// 
// A git directory applies this method of version control to a database.
// You have a directory "database/". For every file you have a directory,
// e.g. "a/" and "b/". This can for example be icons. In every directory
// you might have the source image, and xml-file with tags and derived images
// (png-files, files with changed color, smaller/larger icons, ...). The first
// two files are subject to version control, the other don't.

// (1) when the user requests a file for editing, call
//   $directory=new git_directory("path/in/data_dir");
//   $commit_id=$directory->commit_start();
//   $file_a=$directory->get_file("a");
//   $icon=$file_a->load("file.svg");
// if you just want the file for displaying skip the commit_start()-line.
//
// (2) you can further save a changed version of the file:
//   $directory=new git_directory("path/in/data_dir");
//   $directory->set_commit($commit_id);
//   $file_a=$directory->get_file("a");
//   $file_a->save("file.svg", $content);
//
// (3) if you are finished editing, do:
//   $directory=new git_directory("path/in/data_dir");
//   $directory->set_commit($commit_id);
//   $error=$directory->commit_end("message what you changed");
//
// if error is !=0 we had an error merging that commit (which was created 
// on a branch) to the master. The error contains the id of the current master
// version:
//   $error=('status'=>"merge failed",
//           'files'=>array("file.svg"), 'version'=>'1a2b3c4...', 
//           'branch'=>$commit_id, 'branch_head'=>'2a3b4c5...' );
//
// (4) You have to create a resolve commit:
//   $commit_id=$directory->commit_start($error);
//   $file_a=$directory->get_file("a");
//   $file_a->load("file.svg", $error['branch']) <- the file with conflicts
//   $file_a1->load("file.svg", $error['version']) <- the file on master head
//   $file_a2->load("file.svg", $error['branch_head']) <- the file on branch 
//                                                   head before the conflict
// Resolve the error and save as in (2) and (3)
class git_file {
  var $directory;
  var $id;
  var $files;

  function __construct($directory, $id, $files=array()) {
    $this->directory=$directory;
    $this->id=$id;
    $this->files=$files;
  }

  function url($file, $version_branch=0) {
    $p=array();
    $p[]="directory=".$this->directory->path;
    $p[]="git_file=".$this->id;
    $p[]="file=".$file;
    if($version)
      $p[]="version=".$version_branch;

    return "git_download.php?"+implode("&", $p);
  }

  function load($file, $version_branch=0) {
    $this->directory->lock();
    $this->directory->chdir();

    if($version_branch) {
      $this->directory->exec("git checkout $version_branch");
    }

    $content=file_get_contents("$this->id/$file");
    $version=$this->directory->version(null, 1);

    $finfo=finfo_open(FILEINFO_MIME_TYPE);
    $mime=finfo_file($finfo, "$this->id/$file");
    finfo_close($finfo);

    if($version_branch) {
      $this->directory->exec("git checkout master");
    }

    $this->directory->chback();
    $this->directory->unlock();

    return array(
      "content"=>$content,
      "version"=>$version,
      "mime"=>$mime,
    );
  }

  function save($file, $content) {
    global $data_dir;

    if(!$this->directory->commit_data) {
      return array("status"=>"No commit started.");
    }

    $this->directory->commit_open();
    $this->directory->chdir();

    $f=fopen("{$this->id}/$file", "w");
    fwrite($f, $content);
    fclose($f);

    $this->directory->exec("git add {$this->id}/$file");

    $this->directory->chback();
    $this->directory->commit_close();

    if(!in_array($file, $this->files))
      $this->files[]=$file;

    $this->directory->chback();

    return 0;
  }

  function save_untracked($file, $content) {
    if(!$this->directory->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    $this->directory->chdir();

    if(in_array($file, $this->files)) {
      return array("status"=>"File is under version management");
    }

    $f=fopen("{$this->id}/$file", "w");
    fwrite($f, $content);
    fclose($f);

    $this->directory->chback();

    return 0;
  }

  function xml($parent, $xml) {
    $r=dom_create_append($parent, "git_file", $xml);
    $r->setAttribute("id", $this->id);

    foreach($this->files as $file) {
      $f=dom_create_append($r, "file", $xml);
      $x=dom_create_append_text($f, $file, $xml);
    }
  }
}

// a git_directory is a database which is handled by git. Every object
// (git_file or descendant) resides in an own directory and can contain several
// files, some of them might not be subject to version control.
class git_directory {
  var $path;
  var $file_proto;
  var $commit_data=null;
  var $is_sane=false;
  var $log="";

  // __construct(path, prototype)
  // path       ... the path which is handled by git_directory
  // file_proto ... the prototype for all "files" in the repository, 
  //                defaults to git_file
  function __construct($path, $file_proto="git_file") {
    $this->path=$path;
    $this->file_proto=$file_proto;

    $this->check_state();
  }

  function exec($command, $stdin=0) {
    global $data_dir;
    $ret="";
    $this->log.="> $command\n";

    $descriptors=array(
      0=>array("pipe", "r"),
      1=>array("pipe", "w"),
      2=>array("pipe", "w"));

    $p=proc_open($command, $descriptors, $pipes, "{$data_dir}/{$this->path}");
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
    global $data_dir;

    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    lock_dir("{$data_dir}/{$this->path}");
  }

  function unlock() {
    global $data_dir;

    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    unlock_dir("{$data_dir}/{$this->path}");
  }

  function chdir() {
    global $data_dir;

    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    $this->last_cwd=getcwd();
    chdir("{$data_dir}/{$this->path}");
  }

  function chback() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    if($this->last_cwd)
      chdir($this->last_cwd);
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
    global $data_dir;

    // $lists_dir set?
    if(!$this->path) {
      return "No path set!";
    }

    // No directory to change into ...
    if(!file_exists("{$data_dir}/{$this->path}")) {
      return "path '$this->path' does not exist!";
    }

    $this->is_sane=true;
    $this->chdir();

    // Check if git repository is ready
    if(!file_exists(".git")) {
      $this->exec("git init");
      $this->exec("git commit -m 'Init' --allow-empty");

      if(!file_exists("$this->path/.git")) {
	return "Could not create git repository!";
	$this->is_sane=false;
      }
    }

    $this->chback();

    return true;
  }

  // file_list
  // returns an array with all files handled by git
  //
  // return:
  //   array(
  //     id1 => git_file ....
  //     id2 => git_file ....
  //   )
  function file_list() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    $ret=array();
    $this->lock();

    $this->chdir();

    $d=$this->exec("git ls-files");
    $d=explode("\n", $d);
    foreach($d as $f) {
      if(preg_match("/^(.*)\/(.*)$/", $f, $m)) {
	$list[$m[1]][]=$m[2];
      }
    }

    $this->chback();

    foreach($list as $id=>$files) {
      $ret[$id]=new $this->file_proto($this, $id, $files);
    }

    $this->unlock();
    return $ret;
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

  function set_commit($commit_id) {
    $this->commit_data=$commit_id;
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

    $this->commit_data=$branch;
    $this->chback();
    $this->unlock();

    return $branch;
  }

  function commit_continue($branch) {
    $this->commit_data=$branch;
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

    if(!$this->commit_data) {
      return array("status"=>"No commit started");
    }

    $author=$current_user->get_author();
    $branch=$this->commit_data;
    $this->chdir();

    $this->exec("git checkout $branch");
    $this->exec("git commit --allow-empty --amend -m '$message' --author='$author'");

    $branch_head=$this->version();

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
		   'branch'=>$this->commit_data,
		   'branch_head'=>$branch_head,
		  );
    }
    else {
      $this->exec("git checkout master");
      $this->exec("git merge $branch");
      $this->exec("git branch -d $branch");
    }

    $this->unlock();
    unset($this->commit_data);
    $this->chback();

    return $error;
  }

  function commit_cancel() {
    if(!$this->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    if(!$this->commit_data) {
      return array("status"=>"No commit started");
    }

    $this->lock();

    $this->exec("git branch -D {$this->commit_data}");

    $this->unlock();

    return 0;
  }

  function commit_open() {
    $this->lock();
    $this->chdir();

    $this->exec("git checkout {$this->commit_data}");
  }

  function commit_close() {
    $this->exec("git commit --allow-empty -m 'temporary message' --amend");
    $this->exec("git checkout master");

    $this->unlock();
    $this->chback();
  }

  function create_file($id) {
    if(!$this->commit_data) {
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

function ajax_git_directory_load($param, $xml) {
  $dir=new git_directory("{$param['path']}");
  $dir->xml($xml);
}

function ajax_git_commit_start($param, $xml) {
  $dir=new git_directory($param['path']);
  $result=$dir->commit_start($param);
  return $result;
}

function ajax_git_create_file($param, $xml) {
  $dir=new git_directory($param['path']);
  $dir->commit_continue($param['commit_data']);
  $result=$dir->create_file($param['id']);

  $ret=dom_create_append($xml, "result", $xml);
  $ret->setAttribute("id", $result->id);
  foreach($result->files as $f) {
    $ret1=dom_create_append($ret, "file", $xml);
    dom_create_append_text($ret1, $f, $xml);
  }
}

function ajax_git_commit_end($param, $xml) {
  $dir=new git_directory($param['path']);
  $dir->commit_continue($param['commit_data']);
  $result=$dir->commit_end($param['message']);
  return $result;
}

function ajax_git_commit_cancel($param, $xml) {
  $dir=new git_directory($param['path']);
  $dir->commit_continue($param['commit_data']);
  $result=$dir->commit_cancel();
  return $result;
}

function ajax_git_file_save($param) {
  $dir=new git_directory($param['path']);
  $dir->commit_continue($param['commit_data']);
  $git_file=$dir->get_file($param['git_file']);
  $result=$git_file->save($param['file'], $param['content']);
  return $result;
}
