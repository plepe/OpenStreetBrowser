<?
function basemap_init($renderd) {
  global $plugins_dir;

  $prefix="$plugins_dir/basemap/base";
  $path="$plugins_dir/basemap";

  if(filemtime("$prefix.mml")>filemtime("$prefix.mapnik")) {
    print "Recompiling basemap/base\n";
    cascadenik_compile("$prefix.mml", $path);
  }

  $renderd['basemap_base']=array("file"=>"$prefix.mapnik");
}

register_hook("renderd_get_maps", "basemap_init");
