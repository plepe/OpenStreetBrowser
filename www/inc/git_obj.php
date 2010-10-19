<?
// see file of git_master for some documentation about this git-thingy
include_once "git_dir.php";

// A git_obj is an object in a directory of a git master. It can contain
// several files, some of them might not be subject to version control.
class git_obj {
  var $dir;
  var $id;
  var $files;

  function __construct($dir, $id, $files=array()) {
    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $this->id)) {
      return array("status"=>"Invalid Filename");
    }

    $this->dir=$dir;
    $this->id=$id;
    $this->files=$files;
  }

  function lock() {
    return $this->dir->lock();
  }

  function unlock() {
    return $this->dir->unlock();
  }

  function chdir($path="") {
    $this->dir->chdir("{$this->id}/{$path}");
  }

  function chback() {
    $this->dir->chback();
  }

  function commit_open() {
    return $this->dir->commit_open();
  }

  function commit_close() {
    return $this->dir->commit_close();
  }

  function commit_id() {
    return $this->dir->commit_id();
  }

  function version($branch="", $nolock=0) {
    return $this->dir->version($branch, $nolock);
  }

  function url($file, $version_branch=0) {
    $p=array();
    $p[]="dir=".$this->dir->id;
    $p[]="obj=".$this->id;
    $p[]="file=".$file;
    if($version)
      $p[]="version=".$version_branch;

    return "git_download.php?".implode("&", $p);
  }

  function path($path="") {
    return $this->dir->path("{$this->id}/{$path}");
  }

  function exec($command, $stdin=0, $path="") {
    $this->dir->exec($command, $stdin, "{$this->id}/{$path}");
  }

  function load($file, $version_branch=0) {
    $this->lock();
    $this->chdir();

    if($version_branch) {
      $this->exec("git checkout $version_branch");
    }

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $file)) {
      return array("status"=>"Invalid Filename");
    }

    $content=file_get_contents("$file");
    $version=$this->version(null, 1);

    $finfo=finfo_open(FILEINFO_MIME_TYPE);
    $mime=finfo_file($finfo, "$file");
    finfo_close($finfo);

    if($version_branch) {
      $this->exec("git checkout master");
    }

    $this->chback();
    $this->unlock();

    return array(
      "content"=>$content,
      "version"=>$version,
      "mime"=>$mime,
    );
  }

  function save($file, $content) {
    global $data_dir;

    if(!$this->commit_id()) {
      return array("status"=>"No commit started.");
    }

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $file)) {
      return array("status"=>"Invalid Filename");
    }

    $this->commit_open();
    $this->chdir();

    $f=fopen($file, "w");
    fwrite($f, $content);
    fclose($f);

    $this->exec("git add \"{$file}\"");

    $this->chback();
    $this->commit_close();

    if(!in_array($file, $this->files))
      $this->files[]=$file;

    return 0;
  }

  function remove() {
    global $data_dir;

    if(!$this->commit_id()) {
      return array("status"=>"No commit started.");
    }

    $this->commit_open();
    $this->chdir();

    $this->exec("git rm -r -f \"{$this->id}/\"");
    $this->exec("rm -r -f \"{$this->id}/\"");

    $this->chback();
    $this->commit_close();

    $this->chback();

    return 0;
  }

  function remove_file($file) {
    global $data_dir;

    if(!$this->commit_id()) {
      return array("status"=>"No commit started.");
    }

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $file)) {
      return array("status"=>"Invalid Filename");
    }

    $this->commit_open();
    $this->chdir();

    $this->exec("git rm -f \"$file\"");

    $this->chback();
    $this->commit_close();

    $p=array_search($file, $this->files);
    unset($this->files[$p]);
    print_r($this->files);

    return 0;
  }

  function save_untracked($file, $content) {
    if(!$this->dir->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $file)) {
      return array("status"=>"Invalid Filename");
    }

    $this->chdir();

    if(in_array($file, $this->files)) {
      return array("status"=>"File is under version management");
    }

    $f=fopen("$file", "w");
    fwrite($f, $content);
    fclose($f);

    $this->chback();

    return 0;
  }

  function remove_untracked($file) {
    if(!$this->dir->is_sane) {
      return array("status"=>"Git directory is not in sane state");
    }

    # Any file/directory starts with . ?
    if(preg_match("/^\.|\/\./", $file)) {
      return array("status"=>"Invalid Filename");
    }

    $this->chdir();

    if(in_array($file, $this->files)) {
      return array("status"=>"File is under version management");
    }

    unlink("$file");

    $this->chback();

    return 0;
  }

  function xml($parent, $xml) {
    $r=dom_create_append($parent, "obj", $xml);
    $r->setAttribute("id", $this->id);

    foreach($this->files as $file) {
      $f=dom_create_append($r, "file", $xml);
      $x=dom_create_append_text($f, $file, $xml);
    }
  }

  function preprocess($files) {
    // you can overwrite this function. Will be called after a successful
    // commit for every changed git_obj with the list of changed files
  }
}


