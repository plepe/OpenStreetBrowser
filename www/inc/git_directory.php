<?
class git_file {
  var $directory;
  var $id;
  var $files;

  function __construct($directory, $id, $files=array()) {
    $this->directory=$directory;
    $this->id=$id;
    $this->files=$files;
  }
}

// a git_directory is a database which is handled by git. Every object
// (git_file or descendant) resides in an own directory and can contain several
// files, some of them might not be subject to version control.
class git_directory {
  var $path;
  var $file_proto;

  // __construct(path, prototype)
  // path       ... the path which is handled by git_directory
  // file_proto ... the prototype for all "files" in the repository, 
  //                defaults to git_file
  function __construct($path, $file_proto="git_file") {
    $this->path=$path;
    $this->file_proto=$file_proto;

    $this->check_state();
  }

  function lock() {
    lock_dir($this->path);
  }

  function unlock() {
    unlock_dir($this->path);
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
    // $lists_dir set?
    if(!$this->path) {
      return "No path set!";
    }

    // No directory to change into ...
    if(!file_exists($this->path)) {
      return "path '$this->path' does not exist!";
    }

    // Check if git repository is ready
    if(!file_exists("$this->path/.git")) {
      chdir($this->path);
      system("git init");
      system("git commit -m 'Init' --allow-empty");

      if(!file_exists("$this->path/.git")) {
	return "Could not create git repository!";
      }
    }

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
    if($state=$this->check_state()!==true) {
      return array('status'=>$state);
    }

    $ret=array();
    $this->lock();

    chdir($this->path);
    $d=popen("git ls-files", "r");
    while($f=fgets($d)) {
      $f=trim($f);
      if(preg_match("/^(.*)\/(.*)$/", $f, $m)) {
	$list[$m[1]][]=$m[2];
      }
    }
    pclose($d);

    foreach($list as $id=>$files) {
      $ret[$id]=new $this->file_proto($this, $id, $files);
    }

    $this->unlock();
    return $ret;
  }
}
