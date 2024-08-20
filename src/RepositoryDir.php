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

  function data ($options) {
    $data = parent::data($options);

    $lang = array_key_exists('lang', $options) ? $options['lang'] : 'en';

    if (file_exists("{$this->path}/lang/{$lang}.json")) {
      $data['lang'] = json_decode(file_get_contents("{$this->path}/lang/en.json"), true);
      $lang = json_decode(file_get_contents("{$this->path}/lang/{$options['lang']}.json"), true);
      foreach ($lang as $k => $v) {
        if ($v !== null && $v !== '') {
          $data['lang'][$k] = $v;
        }
      }
    }

    $d = opendir($this->path);
    while ($f = readdir($d)) {
      if (preg_match("/^([0-9a-zA-Z_\-]+)\.json$/", $f, $m) && $f !== 'package.json') {
        $d1 = json_decode(file_get_contents("{$this->path}/{$f}"), true);
        $d1['format'] = 'json';
        $d1['fileName'] = $f;

	if (!$this->isCategory($d1)) {
	  continue;
	}

        $data['categories'][$m[1]] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'), array('filter'))));
      }

      if (preg_match("/^([0-9a-zA-Z_\-]+)\.yaml$/", $f, $m)) {
        $d1 = yaml_parse(file_get_contents("{$this->path}/{$f}"));
        $d1['format'] = 'yaml';
        $d1['fileName'] = $f;

	if (!$this->isCategory($d1)) {
	  continue;
	}

        $data['categories'][$m[1]] = $d1;
      }

      if (preg_match("/^(detailsBody|popupBody).html$/", $f, $m)) {
	$data['templates'][$m[1]] = file_get_contents("{$this->path}/{$f}");
      }
    }
    closedir($d);

    return $data;
  }

  function languages () {
    $list = [];

    foreach (scandir("{$this->path}/lang/") as $f) {
      if (preg_match('/^([a-zA-Z-]+)\.json$/', $f, $m)) {
        $list[] = $m[1];
      }
    }

    return $list;
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
      return false;
    }

    if (!$this->access($file)) {
      return false;
    }

    if (!file_exists("{$this->path}/{$file}")) {
      return null;
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
