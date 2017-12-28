<?php
function getRepositories () {
  global $config;
  $repositories = array();

  if (isset($config['repositories'])) {
    $repositories = $config['repositories'];
  }
  else {
    $repositories = array(
      'default' => array(
        'path' => $config['categoriesDir'],
      ),
    );
  }

  if (isset($config['repositories_gitea'])) {
    $d1 = opendir($config['repositories_gitea']);
    while ($f1 = readdir($d1)) {
      if (substr($f1, 0, 1) !== '.') {
        $d2 = opendir("{$config['repositories_gitea']}/{$f1}");
        while ($f2 = readdir($d2)) {
          if (substr($f2, 0, 1) !== '.') {
            $f2id = substr($f2, 0, -4);

            $repositories["{$f1}/{$f2id}"] = array(
              'path' => "{$config['repositories_gitea']}/{$f1}/{$f2}",
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

  return $repositories;
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
