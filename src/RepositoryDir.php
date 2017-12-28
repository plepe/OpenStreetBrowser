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

        $data['categories'][$m[1]] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'))));
      }
    }
    closedir($d);

    return $data;
  }
}
