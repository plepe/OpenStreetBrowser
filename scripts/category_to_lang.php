#!/usr/bin/php
<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php include "inc/json_readable_encode.php"; ?>
<?
call_hooks("init", $dummy);
$genders = array("M"=>"male", "F"=>"female", "N"=>"neuter");

$translations = array();

$res = sql_query("select tags from category_rule left join category_current on category_rule.category_id = category_current.category_id order by category_current.category_id is not null");
while($elem = pg_fetch_assoc($res)) {
  $tags = parse_hstore($elem['tags']);

  $match = $tags['match'];
  if(!$match)
    continue;

  $match_ex = explode(",", $match);

  $more = array();
  foreach($match_ex as $match) {
    if($match == "place=continent;country;state;city;county;region;town;island")
      $more = array();

    elseif(preg_match('/^([^; ]*)=(.*)$/', trim($match), $m)) {
      $m[2] = explode(";", $m[2]);
      print_r($m);

      foreach($m[2] as $n)
        $more[] = "{$m[1]}={$n}";
    }
    else {
      print "$match\n";
    }
  }
  $match_ex = $more;

  foreach($match_ex as $match) {
    $match = trim($match);

    if(preg_match("/^type=route (.*)$/", $match, $m))
      $match = $m[1];
    elseif(preg_match("/^highway=\* (.*)$/", $match, $m))
      $match = $m[1];
    elseif(preg_match("/^natural=wetland (.*)$/", $match, $m))
      $match = $m[1];

    if(preg_match("/^(.*)=\*$/", $match, $m))
      $match = $m[1];

    if(strpos($match, ' ') !== false) {
      print "* Ignore '$match'\n";
      continue;
    }

    foreach($tags as $k=>$v) {
      $v1 = explode(";", $v);
      if(sizeof($v1) > 2)
        $v = array("message"=>$v1[1], "!=1"=>$v1[2], "gender"=>$genders[$v1[0]]);
      elseif(sizeof($v1) > 1)
        $v = array("message"=>$v1[0], "!=1"=>$v1[1]);

      if(preg_match("/^name:(.*)$/", $k, $m)) {
        $translations[$m[1]]["tag:{$match}"] = $v;
      }
      elseif(preg_match("/^name$/", $k, $m)) {
        $translations['en']["tag:{$match}"] = $v;
      }
    }
  }
}

foreach($translations as $lang=>$trans) {
  ksort($trans);
  file_put_contents("new/$lang.json", json_readable_encode($trans));
}

print_r($translations);
