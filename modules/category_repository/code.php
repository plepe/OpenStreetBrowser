<?php
$category_repository_cache = array();

function get_category_repository($id, $branch="master") {
  global $category_repository_cache;

  if(!array_key_exists($id, $category_repository_cache))
    $category_repository_cache[$id] = array();

  if(!array_key_exists($branch, $category_repository_cache[$id]))
    $category_repository_cache[$id][$branch] = new CategoryRepository($id, $branch);

  return $category_repository_cache[$id][$branch];
}

class CategoryRepository {
  function __construct($id, $branch="master") {
    $this->id = $id;
    $this->branch = $branch;
  }

  function path() {
    global $data_path;

    return "{$data_path}/categories/{$this->id}";
  }

  function data() {
    $data = json_decode(file_get_contents("{$this->path()}/index.json", "r"), true);

    $data['branch'] = $this->branch;
    $data['other_branches'] = array();

    chdir($this->path());

    $f = popen("git for-each-ref --format '%(refname)%09%(objectname)%09%(authordate:iso8601)%09%(subject)' refs/heads", "r");
    while($r = chop(fgets($f))) {
      $r = explode("\t", $r);
      $head = substr($r[0], 11);

      if($head == $this->branch) {
        $data['rev'] = $r[1];
        $data['last_modified'] = $r[2];
        $data['last_message'] = $r[3];
      }
      else {
        $data['other_branches'][$head] = array(
          'rev' => $r[1],
          'last_modified' => $r[2],
          'last_message' => $r[3],
        );
      }
    }
    pclose($f);

    // read categories and indexes
    $data['categories'] = array();
    $f = popen("git ls-tree -r ". shell_escape($this->branch) ." --name-only", "r");
    while($r = chop(fgets($f))) {
      if(preg_match("/^(.*)\.mapcss$/", $r, $m)) {
        $data['categories'][$m[1]] = get_mapcss_category($this->id, $m[1], $this->branch)->data();
      }
      elseif(preg_match("/^(.*)\.list$/", $r, $m)) {
        $data['categories'][$m[1]] = array(
          'type' => "list",
        );
      }
    }
    pclose($f);

    return $data;
  }
}

function create_category_repository($id) {
  global $data_path;
  global $current_user;
  global $git_commit_options;

  $path = "{$data_path}/categories/{$id}";
  mkdir($path, 0777, true);
  chdir($path);

  system("git init");

  $init_index = array(
    "id" => $id,
    "maintainers" => array($current_user->username)
  );

  file_put_contents("{$path}/index.json",
    json_encode($init_index, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

  system("git add index.json");
  $result = adv_exec("git {$git_commit_options} commit -m 'Initial commit' --author=". shell_escape($current_user->get_author()));

  if($result[0])
    return $result[0];

  return true;
}

function ajax_category_repository_load($param) {
  $category_repository = new CategoryRepository($param['id'], $param['branch']);
  return $category_repository->data();
}

// TODO: remove
register_hook("main_links", function(&$list) {
  $list[] = array(5, "<a href='javascript:category_repository_browser_open(\"main\")'>Repo</a>");
});
