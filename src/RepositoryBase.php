<?php
class RepositoryBase {
  function __construct ($id, $def) {
    $this->def = $def;
    $this->path = $def['path'];
  }

  function timestamp () {
    return null;
  }

  function isEmpty () {
    return false;
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

  function data ($options) {
    $data = array(
      'categories' => array(),
      'templates' => array(),
      'timestamp' => Date(DATE_ISO8601, $this->timestamp()),
      'lang' => array(),
    );

    return $data;
  }

  function unfoldCategories (&$data, &$categories=null) {
    if ($categories === null) {
      $categories = &$data['categories'];
    }

    foreach ($categories as $id => $_category) {
      if (preg_match('/^[0-9]+$/', $id)) {
        $id = $_category['id'];

        if (!array_key_exists($id, $data['categories'])) {
          $category = $_category;
          $data['categories'][$id] = $category;
        }
      } else {
        $category = &$categories[$id];
      }

      if (is_array($category) && $category['type'] === 'index') {
        foreach ($category['subCategories'] as $subIndex => $_subCategory) {
          $subCategory = &$category['subCategories'][$subIndex];

          if (array_key_exists('type', $subCategory)) {
            $data['categories'][$subCategory['id']] = $subCategory;
            if (array_key_exists('subCategories', $subCategory)) {
              $this->unfoldCategories($data, $subCategory['subCategories']);
              $data['categories'][$subCategory['id']]['subCategories'] = array_map(function ($c) {
                return array('id' => $c['id']);
              }, $subCategory['subCategories']);
            }

            $category['subCategories'][$subIndex] = array(
              'id' => $subCategory['id']
            );
          }
        }
      }
    }
  }

  function updateLang (&$data, $options) {
    $lang = array_key_exists('lang', $options) ? $options['lang'] : 'en';

    $this->unfoldCategories($data);

    if (!is_array($data['lang'])) {
      $data['lang'] = array();
    }

    foreach ($data['categories'] as $id => $category) {
      $name = null;
      if (array_key_exists("category:{$id}", $data['lang'])) {
        $name = $data['lang']["category:{$id}"];

        if ($name !== '' && $name !== null) {
          $data['categories'][$id]['name'] = array(
            $lang => $data['lang']["category:{$id}"],
          );
        }
      }
      elseif (is_array($category) && array_key_exists('name', $category)) {
        if (array_key_exists($lang, $category['name'])) {
          $name = $category['name'][$lang];
        }
        elseif (array_key_exists('en', $category['name'])) {
          $name = $category['name']['en'];
        }
        elseif (sizeof($category['name'])) {
          $name = $category['name'][array_keys($category['name'])[0]];
        }

        $data['lang']["category:{$id}"] = $name;

        $data['categories'][$id]['name'] = array($lang => $name);
      }

    }
  }

  function isCategory ($data) {
    if (!array_key_exists('type', $data)) {
      return true;
    }

    return in_array($data['type'], array('index', 'overpass'));
  }
}
