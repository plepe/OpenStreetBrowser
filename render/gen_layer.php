#!/usr/bin/php
<?
include("config_queries.php");
$base    =file_get_contents("template_base.mml");

// When you add more keys here, don't forget to add these keys also to the 
// layer detection in src/sql/02_layer.sql
// THese keys  should also have an index (src/sql/01_indices.sql)

function fit($template, $num=0, $where=0) {
  global $query;

  $rep=array(
    "%LAYER_NUM%"=>$num,
    "%SQL_HIGHWAY_TYPE%"=>$query["highway"],
    "%SQL_LANDUSE%"=>$query["landuse"],
    "%SQL_AMENITY%"=>$query["base_amenity"],
    "%SQL_PLACES%"=>$query["places"]
  );
  foreach($query as $k=>$sql) {
    $rep["%SQL_$k%"]=$sql;
  }

  $rep["%LAYER_WHERE%"]="layer_level=$num";

  return strtr($template, $rep);
}

$base=fit($base);

while(ereg("%INSERTLAYERS ([^%]*)%", $base, $m)) {
  $insert=file_get_contents("$m[1].mml");

  $insert_full="";
  for($l=-5; $l<=5; $l++) {
    $insert_full.= fit($insert, $l);
  }

  $base=strtr($base, array($m[0]=>$insert_full));
}

while(ereg("%INSERTLAYERSBACK ([^%]*)%", $base, $m)) {
  $insert=file_get_contents("$m[1].mml");

  $insert_full="";
  for($l=5; $l>=-5; $l--) {
    $insert_full.= fit($insert, $l);
  }

  $base=strtr($base, array($m[0]=>$insert_full));
}

while(ereg("%INSERT ([^%]*)%", $base, $m)) {
  $insert=file_get_contents("$m[1].mml");
  $insert=fit($insert);

  $base=strtr($base, array($m[0]=>$insert));
}

print $base;
