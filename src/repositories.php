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
    $d1 = opendir($repositoriesGitea);
    while ($f1 = readdir($d1)) {
      if (substr($f1, 0, 1) !== '.') {
        $d2 = opendir("{$repositoriesGitea}/{$f1}");
        while ($f2 = readdir($d2)) {
          if (substr($f2, 0, 1) !== '.') {
            $f2id = substr($f2, 0, -4);

            $result["{$f1}/{$f2id}"] = array(
              'path' => "{$repositoriesGitea}/{$f1}/{$f2}",
              'type' => 'git',
	      'repositoryUrl' => 'https://www.openstreetbrowser.org/dev/{{ repositoryId }}',
	      'categoryUrl' => 'https://www.openstreetbrowser.org/dev/{{ repositoryId }}/src/{{ categoryId }}.json',
            );
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
