<?php
class RepositoryDir extends RepositoryBase {
  function timestamp () {
    $ts = 0;
    $d = opendir($this->path);
    while ($f = readdir($d)) {
      $t = filemtime("{$this->path}/{$f}");
      if ($t > $ts) {
        $ts = $t;
      }
    }
    closedir($d);

    return $ts;
  }

  function data () {
    $data = parent::data();

    $d = opendir($this->path);
    while ($f = readdir($d)) {
      if (preg_match("/^([0-9a-zA-Z_\-]+)\.json$/", $f, $m) && $f !== 'package.json') {
        $d1 = json_decode(file_get_contents("{$this->path}/{$f}"), true);

	if (!$this->isCategory($d1)) {
	  continue;
	}

        $data['categories'][$m[1]] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'), array('filter'))));
      }

      if (preg_match("/^(detailsBody|popupBody).html$/", $f, $m)) {
	$data['templates'][$m[1]] = file_get_contents("{$this->path}/{$f}");
      }
    }
    closedir($d);

    return $data;
  }

  function access ($file) {
    return (substr($file, 0, 1) !== '.' && !preg_match('/\/\./', $file));
  }

  function scandir($path="") {
    if (substr($path, 0, 1) === '.' || preg_match("/\/\./", $path)) {
      return false;
    }

    if (!$this->access($path)) {
      return false;
    }

    return scandir("{$this->path}/{$path}");
  }

  function file_get_contents ($file) {
    if (substr($file, 0, 1) === '.' || preg_match("/\/\./", $file)) {
      return null;
    }

    if (!$this->access($file)) {
      return false;
    }

    return file_get_contents("{$this->path}/{$file}");
  }

  function file_put_contents ($file, $content) {
    if (substr($file, 0, 1) === '.' || preg_match("/\/\./", $file)) {
      return false;
    }

    if (!$this->access($file)) {
      return false;
    }

    return file_put_contents("{$this->path}/{$file}", $content);
  }
}
