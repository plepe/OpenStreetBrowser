<?php
class RepositoryDir {
  function __construct ($id, $def) {
    $this->def = $def;
    $this->path = $def['path'];
  }

  function info () {
    $ret = array();

    foreach (array('name') as $k) {
      if (array_key_exists($k, $this->def)) {
        $ret[$k] = $this->def[$k];
      }
    }

    $ret['timestamp'] = Date(DATE_ISO8601, $this->timestamp());

    return $ret;
  }

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
    $data = array();

    $d = opendir($this->path);
    while ($f = readdir($d)) {
      if (preg_match("/^([0-9a-zA-Z_\-]+)\.json$/", $f, $m) && $f !== 'package.json') {
        $d1 = json_decode(file_get_contents("{$this->path}/{$f}"), true);
        $data[$m[1]] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'))));
      }
    }
    closedir($d);

    return $data;
  }
}
