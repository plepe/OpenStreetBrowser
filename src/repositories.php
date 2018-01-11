<?php
function getRepositories () {
  global $repositories;
  global $repositoriesGitea;
  $result = array();

  if (isset($repositories)) {
    $result = $repositories;
  }
  else {
    $result = array(
      'default' => array(
        'path' => $config['categoriesDir'],
      ),
    );
  }

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
	      $r['categoryUrl'] = "{$repositoriesGitea['url']}/{{ repositoryId }}/src/{{ categoryId }}.json";
            }

            $result["{$f1}/{$f2id}"] = $r;
          }
        }
        closedir($d2);
      }
    }
    closedir($d1);
  }

  return $result;
}

function getRepo ($repoId, $repoData) {
  switch (array_key_exists('type', $repoData) ? $repoData['type'] : 'dir') {
    case 'git':
      $repo = new RepositoryGit($repoId, $repoData);
      break;
    default:
      $repo = new RepositoryDir($repoId, $repoData);
  }

  return $repo;
}

register_hook('init', function () {
  global $repositoriesGitea;

  if (isset($repositoriesGitea) && array_key_exists('url', $repositoriesGitea)) {
    $d = array('repositoriesGitea' => array(
      'url' => $repositoriesGitea['url'],
    ));
    html_export_var($d);
  }
});
