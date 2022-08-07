<?php
register_hook("get-repositories", function ($result) {
  global $repositoriesGitea;

  if (isset($repositoriesGitea)) {
    $d1 = opendir($repositoriesGitea['path']);
    while ($f1 = readdir($d1)) {
      if (substr($f1, 0, 1) !== '.') {
        $d2 = opendir("{$repositoriesGitea['path']}/{$f1}");
        while ($f2 = readdir($d2)) {
          if (substr($f2, 0, 1) !== '.') {
            $f2id = substr($f2, 0, -4);

            $r = array(
              'path' => "{$repositoriesGitea['path']}/{$f1}/{$f2}",
              'type' => 'git',
            );

            if (array_key_exists('url', $repositoriesGitea)) {
	      $r['repositoryUrl'] = "{$repositoriesGitea['url']}/{{ repositoryId }}";
	      $r['categoryUrl'] = "{$repositoriesGitea['url']}/{{ repositoryId }}/src/branch/{{ branchId }}/{{ categoryId }}.json";
            }

            $result["{$f1}/{$f2id}"] = $r;
          }
        }
        closedir($d2);
      }
    }
    closedir($d1);
  }
});

register_hook('init', function () {
  global $repositoriesGitea;

  if (isset($repositoriesGitea) && array_key_exists('url', $repositoriesGitea)) {
    $d = array('repositoriesGitea' => array(
      'url' => $repositoriesGitea['url'],
    ));
    html_export_var($d);
  }
});
