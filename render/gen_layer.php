#!/usr/bin/php
<?
include("config_queries.php");
$base    =file_get_contents("template_base.mml");
$template1=file_get_contents("template_layer1.mml");
$template2=file_get_contents("template_layer2.mml");

// When you add more keys here, don't forget to add these keys also to the 
// layer detection in src/sql/02_layer.sql
// THese keys  should also have an index (src/sql/01_indices.sql)

function fit($template, $num, $where=0) {
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

$base=fit($base, 0);

$layers1="";
for($l=-5; $l<=5; $l++) {
  $layers1.= fit($template1, $l);
}

$layers2="";
for($l=5; $l>=-5; $l--) {
  $layers1.= fit($template2, $l);
}

print strtr($base, array("%LAYERS%"=>$layers1, "%LAYERS2%"=>$layers2));
