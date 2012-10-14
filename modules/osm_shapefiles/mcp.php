<?
function osm_shapefiles_download($id, $url, $param=array()) {
  global $root_path;

  // get file name from url
  preg_match("/\/([^\/]*)$/", $url, $m);
  $file=$m[1];

  print "OSM Shapefile: Check $file\n";

  // Download file
  system("cd $root_path/data/ ; wget --progress=dot:mega --timestamping $url");

  // If file newer (or first time), extract
  if((!file_exists("$root_path/data/$id.timestamp"))||
     (filemtime("$root_path/data/$file")!=filemtime("$root_path/data/$id.timestamp"))) {

    // check additional parameters
    // -> dir
    $tar_param="";
    if($param['dir']) {
      mkdir("$root_path/data/{$param['dir']}");
      $tar_param=" -C {$param['dir']}/";
    }

    // extract file
    if(preg_match("/\.tar\.bz2$/", $file))
      system("cd $root_path/data/ ; tar xjvf $file$tar_param");
    elseif(preg_match("/\.(tgz|tar\.gz)$/", $file))
      system("cd $root_path/data/ ; tar xzvf $file$tar_param");
    else
      print "Unknown type of file: $file\n";

    // remember file time of downloaded file
    system("touch $root_path/data/$id.timestamp --reference=$root_path/data/$file");
  }
}

function osm_shapefiles_init() {
  osm_shapefiles_download("processed_p", "http://tile.openstreetmap.org/processed_p.tar.bz2", array("dir"=>"world_boundaries"));
  osm_shapefiles_download("world_boundaries", "http://tile.openstreetmap.org/world_boundaries-spherical.tgz");
}

register_hook("mcp_start", "osm_shapefiles_init");
