<?
function mapnik_rotate_compiled($file) {
  print "MAPNIK ROTATE: $file\n";
}

register_hook("cascadenik_compiled", "mapnik_rotate_compiled");
