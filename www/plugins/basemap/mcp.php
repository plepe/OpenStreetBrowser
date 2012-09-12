<?
function basemap_init($renderd) {
  global $plugins_dir;
  $compile=false;

  $prefix="$plugins_dir/basemap/base";
  $path="$plugins_dir/basemap";

  if(!file_exists("$prefix.mapnik"))
    $compile=true;
  elseif(filesize("$prefix.mapnik")<1024)
    $compile=true;
  elseif(filemtime("$prefix.mml")>filemtime("$prefix.mapnik"))
    $compile=true;

  if($compile) {
    print "Recompiling basemap/base\n";
    cascadenik_compile("$prefix.mml", $path);
  }

  $renderd['basemap_base']=array("file"=>"$prefix.mapnik");
}

register_hook("renderd_get_maps", "basemap_init");
