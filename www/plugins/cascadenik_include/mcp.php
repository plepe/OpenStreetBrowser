<?
// When you add more keys here, don't forget to add these keys also to the 
// layer detection in src/sql/02_layer.sql
// THese keys  should also have an index (src/sql/01_indices.sql)

function cascadenik_include_fit($template, $path, $num=0, $where=0) {
  global $root_path;
  global $db;
  include("$root_path/render/config_queries.php");

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

  $rep["%LAYER_WHERE%"]="parse_layer(osm_tags)=$num";
  $rep["%LAYER%"]="$num";
  $rep["%ROOT_PATH%"]=$root_path;
  $rep["%DB_HOST%"]=$db['host'];
  $rep["%DB_USER%"]=$db['user'];
  $rep["%DB_NAME%"]=$db['name'];
  $rep["%DB_PASS%"]=$db['passwd'];

  return strtr($template, $rep);
}

function cascadenik_include_compile($file, $path) {
  global $tmp_dir;
  $base=cascadenik_include_fit(file_get_contents($file), $path);
  print "FILE: $file\n";

  while(ereg("%INSERTLAYERS ([^%]*)%", $base, $m)) {
    $insert=file_get_contents("$path/$m[1].inc_mml");

    $insert_full="";
    for($l=-5; $l<=5; $l++) {
      $insert_full.= cascadenik_include_fit($insert, $path, $l);
    }

    $base=strtr($base, array($m[0]=>$insert_full));
  }

  while(ereg("%INSERTLAYERSBACK ([^%]*)%", $base, $m)) {
    $insert=file_get_contents("$path/$m[1].inc_mml");

    $insert_full="";
    for($l=5; $l>=-5; $l--) {
      $insert_full.= cascadenik_include_fit($insert, $path, $l);
    }

    $base=strtr($base, array($m[0]=>$insert_full));
  }

  while(ereg("%INSERT ([^%]*)%", $base, $m)) {
    $insert=file_get_contents("$path/$m[1].inc_mml");
    $insert=cascadenik_include_fit($insert, $path);

    $base=strtr($base, array($m[0]=>$insert));
  }

  $file="$tmp_dir/".uniqid().".mml";
  file_put_contents($file, $base);
}

register_hook("cascadenik_compile", "cascadenik_include_compile");
