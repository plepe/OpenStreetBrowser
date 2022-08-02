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

      $stmt = $db->prepare("update customCategory set lastAccess=:now where id=:id");
      $stmt->bindValue(':id', $param['id']);
      $stmt->bindValue(':now', (new DateTime())->format('Y-m-d H:i:s'), PDO::PARAM_STR);
      $stmt->execute();

      return $result;
    }

    return false;
  }

  if ($param['content']) {
    $id = md5($param['content']);

    //$stmt = $db->prepare("insert into customCategory (id, content) values (:id, :content) on duplicate key update lastAccess=:now");
    $stmt = $db->prepare("insert into customCategory (id, content) values (:id, :content) on conflict(id) do update set lastAccess=:now");
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->bindValue(':content', $param['content'], PDO::PARAM_STR);
    $stmt->bindValue(':now', (new DateTime())->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $result = $stmt->execute();
    return $result;
  }
}
