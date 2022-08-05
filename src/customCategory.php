<?php
function ajax_customCategory ($param) {
  global $db;

  if (!$db) {
    return null;
  }

  if ($param['id']) {
    $stmt = $db->prepare("select content from customCategory where id=:id");
    $stmt->bindValue(':id', $param['id'], PDO::PARAM_STR);
    if ($stmt->execute()) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $result = $row['content'];
      $stmt->closeCursor();

      customCategoryUpdateAccess($param['id']);

      return $result;
    }

    return false;
  }

  if ($param['content']) {
    $id = md5($param['content']);

    $stmt = $db->prepare("insert or ignore into customCategory (id, content) values (:id, :content)");
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->bindValue(':content', $param['content'], PDO::PARAM_STR);
    $result = $stmt->execute();

    customCategoryUpdateAccess($id);

    return $result;
  }
}

function customCategoryUpdateAccess ($id) {
  global $db;

  if (!isset($_SESSION['customCategoryAccess'])) {
    $_SESSION['customCategoryAccess'] = [];
  }

  // update access per session only once a day
  if (array_key_exists($id, $_SESSION['customCategoryAccess']) && $_SESSION['customCategoryAccess'][$id] > time() - 86400) {
    return;
  }

  $_SESSION['customCategoryAccess'][$id] = time();

  $stmt = $db->prepare("insert into customCategoryAccess (id) values (:id)");
  $stmt->bindValue(':id', $id);
  $stmt->execute();
}
