<?
function basemap_init($renderd) {
  global $plugins_dir;

  $prefix="$plugins_dir/basemap/base";

  if(filemtime("$prefix.mml")>filemtime("$prefix.mapnik")) {
    print "Recompiling basemap/base\n";
    cascadenik_compile("$prefix.mml");
  }

  renderd_register(&$renderd, "basemap_base", "$prefix.mapnik");
}

register_hook("build_renderd", "basemap_init");
