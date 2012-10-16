<?
function basemap_init($renderd) {
  $compile=false;

  $prefix=modulekit_file("basemap", "base");
  $path=modulekit_file("basemap", "");

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
