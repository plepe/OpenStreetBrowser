#!/usr/bin/php
<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
function json_readable_encode($in, $indent_string = "    ", $indent = 0, Closure $_escape = null)
{
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
      return json_encode($in, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }

    if (__CLASS__ && isset($this))
    {
        $_myself = array($this, __FUNCTION__);
    }
    elseif (__CLASS__)
    {
        $_myself = array('self', __FUNCTION__);
    }
    else
    {
        $_myself = __FUNCTION__;
    }

    if (is_null($_escape))
    {
        $_escape = function ($str)
        {
            return str_replace(
                array('\\', '"', "\n", "\r", "\b", "\f", "\t", '\\\\u'),
                array('\\\\', '\\"', "\\n", "\\r", "\\b", "\\f", "\\t", '\\u'),
                $str);
        };
    }

    $out = '';

    // TODO: format value (unicode, slashes, ...)
    if((!is_array($in)) && (!is_object($in)))
      return json_encode($in);

    // see http://stackoverflow.com/a/173479
    $is_assoc = array_keys($in) !== range(0, count($in) -1);

    foreach ($in as $key=>$value)
    {
        if($is_assoc) {
          $out .= str_repeat($indent_string, $indent + 1);
          $out .= "\"".$_escape((string)$key)."\": ";
        }
        else {
          $out .= str_repeat($indent_string, $indent + 1);
        }

        if ((is_object($value) || is_array($value)) && (!count($value))) {
            $out .= "[]";
        }
        elseif (is_object($value) || is_array($value))
        {
            $out .= call_user_func($_myself, $value, $indent_string, $indent + 1, $_escape);
        }
        elseif (is_bool($value))
        {
            $out .= $value ? 'true' : 'false';
        }
        elseif (is_null($value))
        {
            $out .= 'null';
        }
        elseif (is_string($value))
        {
            $out .= "\"" . $_escape($value) ."\"";
        }
        else
        {
            $out .= $value;
        }

        $out .= ",\n";
    }

    if (!empty($out))
    {
        $out = substr($out, 0, -2);
    }

    if($is_assoc) {
      $out =  "{\n" . $out;
      $out .= "\n" . str_repeat($indent_string, $indent) . "}";
    }
    else {
      $out = "[\n" . $out;
      $out .= "\n" . str_repeat($indent_string, $indent) . "]";
    }

    return $out;
}
call_hooks("init", $dummy);
$genders = array("M"=>"male", "F"=>"female", "N"=>"neuter");

$translations = array();

if(sizeof($argv) < 3) {
  print "category_export_translations <category> <dest_dir>\n";
  exit(1);
}

$category = $argv[1];
$dest_dir = $argv[2];

$res = sql_query("select rule_id as id, tags from category_rule left join category_current on category_rule.category_id = category_current.category_id where category_current.category_id='" . mysql_escape_string($category). "' order by category_current.category_id is not null");
while($elem = pg_fetch_assoc($res)) {
  //$id = "rule{$elem['id']}";
  $tags = parse_hstore($elem['tags']);

  $id = $tags['match'];
  //$translations['match'][$id] = $tags['match'];

  foreach($tags as $k=>$v) {
    $v1 = explode(";", $v);
    if(sizeof($v1) > 2)
      $v = array("message"=>$v1[1], "!=1"=>$v1[2], "gender"=>$genders[$v1[0]]);
    elseif(sizeof($v1) > 1)
      $v = array("message"=>$v1[0], "!=1"=>$v1[1]);

    if(preg_match("/^name:(.*)$/", $k, $m)) {
      $translations[$m[1]][$id] = $v;
    }
    elseif(preg_match("/^name$/", $k, $m)) {
      $translations['en'][$id] = $v;
    }
  }
}

foreach($translations as $lang=>$trans) {
  ksort($trans);
  file_put_contents("{$dest_dir}/{$lang}.json", json_readable_encode($trans));
}

print_r($translations);
