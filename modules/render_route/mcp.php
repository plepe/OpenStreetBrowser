<?
function render_route_init($renderd) {
  $prefix=modulekit_file("render_route", "overlay_pt");

  if(filemtime("$prefix.mml")>filemtime("$prefix.mapnik")) {
    print "Recompiling render_route/overlay_pt\n";
    cascadenik_compile("$prefix.mml");
    mapnik_colorsvg_process("$prefix.mapnik");
    mapnik_rotate_process("$prefix.mapnik");
  }

  $renderd['overlay_pt']=array("file"=>"$prefix.mapnik");
}

register_hook("renderd_get_maps", "render_route_init");
