<?php
class RepositoryBase {
  function __construct ($id, $def) {
    $this->def = $def;
    $this->path = $def['path'];
  }

  function timestamp () {
    return null;
  }

  function info () {
    $ret = array();

    foreach (array('name') as $k) {
      if (array_key_exists($k, $this->def)) {
        $ret[$k] = $this->def[$k];
      }
    }

    $ret['timestamp'] = Date(DATE_ISO8601, $this->timestamp());

    return $ret;
  }

  function data () {
    $data = array(
      'categories' => array(),
      'timestamp' => Date(DATE_ISO8601, $this->timestamp()),
    );

    return $data;
  }

  function isCategory ($data) {
    if (!array_key_exists('type', $data)) {
      return false;
    }

    return in_array($data['type'], array('index', 'overpass'));
  }
}
