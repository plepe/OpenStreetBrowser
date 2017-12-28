<?php
class RepositoryGit {
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
    $ts = (int)shell_exec("cd " . escapeShellArg($this->path) . "; git log -1 --pretty=format:%ct");

    return $ts;
  }

  function data () {
    $data = array(
      'categories' => array(),
      'timestamp' => Date(DATE_ISO8601, $this->timestamp()),
    );

    $d = popen("cd " . escapeShellArg($this->path) . "; git ls-tree HEAD", "r");
    while ($r = fgets($d)) {
      if (preg_match("/^[0-9]{6} blob [0-9a-f]{40}\t(([0-9a-zA-Z_\-]+)\.json)$/", $r, $m)) {
        $f = $m[1];
        $id = $m[2];

        if ($f === 'package.json') {
          continue;
        }

        $d1 = json_decode(shell_exec("cd " . escapeShellArg($this->path) . "; git show HEAD:" . escapeShellArg($f)), true);
        $data['categories'][$id] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'))));
      }
    }
    pclose($d);

    return $data;
  }
}
