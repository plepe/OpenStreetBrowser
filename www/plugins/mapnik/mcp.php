<?
function mapnik_build_renderd($renderd) {
  global $plugins_list;
  global $plugins_dir;

  call_hooks("mapnik_compile");

  print "MAPNIK BUILD RENDERD\n";
  foreach($plugins_list as $plugin=>$tags) {
    $d=opendir("$plugins_dir/$plugin");
    while($r=readdir($d)) {
      if((substr($r, 0, 1)!=".")&&(preg_match("/^(.*)\.mapnik$/", $r, $m))) {
	$style="{$plugin}_{$m[1]}";
	print "Found style $style\n";

	$renderd.="[$style]\n";
	$renderd.="URI=/tiles/$style/\n";
	$renderd.="XML=$plugins_dir/$plugin/$r\n";
	$renderd.="HOST=dummy.host\n";
	$renderd.="\n";
      }
    }
    closedir($d);
  }
}

register_hook("build_renderd", "mapnik_build_renderd");
