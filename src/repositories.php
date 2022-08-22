<?php
function getRepositories () {
  global $repositories;
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

  call_hooks("get-repositories", $result);

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
