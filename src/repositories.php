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
