<?php
class RepositoryGit extends RepositoryBase {
  function __construct ($id, $def) {
    parent::__construct($id, $def);
    if (array_key_exists('branch', $def)) {
      $this->branch = $def['branch'];
    }
    else {
      $this->branch = chop(shell_exec("cd " . escapeShellArg($this->path) . "; git rev-parse --abbrev-ref HEAD"));
    }

    $this->branchEsc = escapeShellArg($this->branch);
  }

  function setBranch ($branch) {
    $this->branch = $branch;
    $this->branchEsc = escapeShellArg($this->branch);

    exec("cd " . escapeShellArg($this->path) . "; git ls-tree {$this->branchEsc}", $output, $return);

    if ($return !== 0) {
      throw new Exception('no such branch');
    }
  }

  function timestamp () {
    if (!isset($this->_timestamp)) {
      $ts = shell_exec("cd " . escapeShellArg($this->path) . "; git log -1 --all --pretty=format:%ct");

      $this->_timestamp = $ts === null ? null : (int)$ts;
    }

    return $this->_timestamp;
  }

  function isEmpty () {
    if ($this->timestamp() === null) {
      return true;
    }

    if (!sizeof($this->scandir('.'))) {
      return true;
    }

    return false;
  }

  function data ($options) {
    $data = parent::data($options);

    $lang = array_key_exists('lang', $options) ? $options['lang'] : 'en';

    if (true) {
      $data['lang'] = json_decode(shell_exec("cd " . escapeShellArg($this->path) . "; git show {$this->branchEsc}:lang/en.json 2>/dev/null"), true);
      $lang = json_decode(shell_exec("cd " . escapeShellArg($this->path) . "; git show {$this->branchEsc}:lang/" . escapeShellArg("{$options['lang']}.json") . " 2>/dev/null"), true);
      foreach ($lang as $k => $v) {
        if ($v !== null && $v !== '') {
          $data['lang'][$k] = $v;
        }
      }
    }

    $d = popen("cd " . escapeShellArg($this->path) . "; git ls-tree {$this->branchEsc}", "r");
    while ($r = fgets($d)) {
      if (preg_match("/^[0-9]{6} blob [0-9a-f]{40}\t(([0-9a-zA-Z_\-]+)\.json)$/", $r, $m)) {
        $f = $m[1];
        $id = $m[2];

        if ($f === 'package.json') {
          continue;
        }

        $d1 = json_decode(shell_exec("cd " . escapeShellArg($this->path) . "; git show {$this->branchEsc}:" . escapeShellArg($f)), true);

	if (!$this->isCategory($d1)) {
	  continue;
	}

        $data['categories'][$id] = jsonMultilineStringsJoin($d1, array('exclude' => array(array('const'), array('filter'))));
      }

      if (preg_match("/^([0-9a-zA-Z_\-]+)\.yaml$/", $r, $m)) {
        $d1 = yaml_parse(shell_exec("cd " . escapeShellArg($this->path) . "; git show {$this->branchEsc}:" . escapeShellArg($f)));

	if (!$this->isCategory($d1)) {
	  continue;
	}

        $data['categories'][$m[1]] = $d1;
      }

      if (preg_match("/^[0-9]{6} blob [0-9a-f]{40}\t((detailsBody|popupBody)\.html)$/", $r, $m)) {
	$data['templates'][$m[2]] = shell_exec("cd " . escapeShellArg($this->path) . "; git show {$this->branchEsc}:" . escapeShellArg($m[1]));
      }
    }
    pclose($d);

    if (!array_key_exists('branch', $this->def)) {
      $d = popen("cd " . escapeShellArg($this->path) . "; git for-each-ref --sort=-committerdate refs/heads/", "r");
      $data['branch'] = $this->branch;
      $data['branches'] = array();
      while ($r = fgets($d)) {
        if (preg_match("/^([0-9a-f]{40}) commit\trefs\/heads\/(.*)$/", $r, $m)) {
          $data['branches'][$m[2]] = array(
            'commit' => $m[1],
          );
        }
      }
      pclose($d);
    }

    return $data;
  }

  function scandir($path="") {
    if ($path !== '' && substr($path, -1) !== '/') {
      $path .= '/';
    }

    $d = popen("cd " . escapeShellArg($this->path) . "; git ls-tree {$this->branchEsc} " . escapeShellArg($path), "r");
    $ret = array();
    while ($r = fgets($d)) {
      $ret[] = chop(substr($r, 53));
    }
    pclose($d);

    $ret = array_filter(
      $ret,
      function ($file) {
	return preg_match("/\.(html|json)$/", $file);
      }
    );

    return $ret;
  }

  function file_get_contents ($file) {
    return shell_exec("cd " . escapeShellArg($this->path) . "; git show {$this->branchEsc}:" . escapeShellArg($file));
  }
}
