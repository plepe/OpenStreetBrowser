<?
// see file of git_master for some documentation about this git-thingy
include_once "git_master.php";

// A git_dir is a directory of objects in a git master
class git_dir {
  var $id;
  var $master;
  var $obj_proto;
  var $is_sane=false;
  var $log="";

  // __construct(path, prototype)
  // path       ... the path which is handled by git_directory
  // file_proto ... the prototype for all "files" in the repository, 
  //                defaults to git_file
  function __construct($master, $id, $obj_proto="git_obj") {
    $this->id=$id;
    $this->obj_proto=$obj_proto;
    $this->master=$master;
    $master->register_dir($this);

    $this->is_sane=$this->check_state();
  }

  function path($path="") {
    return $this->master->path("{$this->id}/{$path}");
  }

  function check_state() {
    if(!$this->master->check_state()) {
      return false;
    }

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $this->id)) {
      return array("status"=>"Invalid Filename");
    }

    if(!file_exists($this->path())) {
      $this->master->commit_start();
      mkdir($this->path());
      touch("{$this->path()}/.dummy");
      $this->master->exec("git add \"{$this->id}\"");
      $this->master->commit_end("Created directory '{$this->id}'");
    }
  }

  function exec($command, $stdin=0, $path="") {
    return $this->master->exec($command, $stdin=0, "{$this->id}/{$path}");
  }

  function lock() {
    return $this->master->lock();
  }

  function unlock() {
    return $this->master->unlock();
  }

  function chdir($path="") {
    $this->master->chdir("{$this->id}/{$path}");
  }

  function chback() {
    $this->master->chback();
  }

  // obj_list
  // returns an array with all objects in this directory
  //
  // return:
  //   array(
  //     id1 => git_obj ....
  //     id2 => git_obj ....
  //   )
  function obj_list() {
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
      $ret[$id]=new $this->obj_proto($this, $id, $files);
    }

    $this->unlock();
    return $ret;
  }

  // version()
  // Returns the current version
  function version($branch="", $nolock=0) {
    return $this->master->version($branch, $nolock);
  }

  function commit_continue($branch) {
    $this->master->commit_continue($branch);
  }

  function commit_id() {
    return $this->master->commit_id();
  }

  function commit_open() {
    return $this->master->commit_open();
  }

  function commit_close() {
    return $this->master->commit_close();
  }

  function preprocess($changed_list) {
    foreach($changed_list as $id=>$files) {
      $files=array_unique($files);
      $obj=$this->get_obj($id);
      $obj->preprocess($files);
    }
  }

  function preprocess_all() {
    $list=$this->obj_list();

    foreach($list as $id=>$obj) {
      $obj->preprocess();
    }
  }

  function create_obj($id) {
    if(!$this->commit_id()) {
      return array("status"=>"No commit started.");
    }

    if(!$id)
      $id=uniqid();
    //else
      // check if not used yet

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $id)) {
      return array("status"=>"Invalid Filename");
    }

    $this->master->commit_open();

    $this->chdir();
    mkdir("$id/");
    touch("{$id}/.dummy");

    $this->exec("git add \"$id/\"");

    $this->master->commit_close();

    $obj=new $this->obj_proto($this, $id, array());

    $this->chback();
    return $obj;
  }

  function get_obj($id) {
    if($this->commit_id())
      $this->master->commit_open();

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $id)) {
      return array("status"=>"Invalid Filename");
    }

    $r=$this->exec("git ls-files \"$id/\"");

    if($this->commit_id())
      $this->master->commit_close();

    $r=explode("\n", $r);
    $list=array();
    foreach($r as $f) {
      if(preg_match("/^$id\/(.*)$/", $f, $m))
	$list[]=$m[1];
    }

    if(!sizeof($list)) {
      return null;
    }

    $obj=new $this->obj_proto($this, $id, $list);

    return $obj;
  }

  function xml($xml) {
    $res=dom_create_append($xml, "result", $xml);

    $list=$this->obj_list();
    foreach($list as $l) {
      $l->xml($res, $xml);
    }
  }
}


