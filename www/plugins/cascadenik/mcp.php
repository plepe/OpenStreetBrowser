<?
function cascadenik_compile() {
  global $plugins_list;
  global $plugins_dir;

  print "MAPNIK BUILD RENDERD\n";
  foreach($plugins_list as $plugin=>$tags) {
    $d=opendir("$plugins_dir/$plugin");
    while($r=readdir($d)) {
      if((substr($r, 0, 1)!=".")&&(preg_match("/^(.*)\.mml$/", $r, $m))) {
	print "Cascadenik: Found $plugin -> $m[1]\n";

	$file="$plugins_dir/$plugin/$m[1].mml";

	call_hooks("cascadenik_compile", &$file, "$plugins_dir/$plugin");

	print "Cascadenik process file $file\n";
	system("cascadenik-compile.py $file $plugins_dir/$plugin/$m[1].xml");
	rename("$plugins_dir/$plugin/$m[1].xml", "$plugins_dir/$plugin/$m[1].mapnik");

	call_hooks("cascadenik_compiled", "$plugins_dir/$plugin/$m[1].mapnik");
      }
    }
    closedir($d);
  }
}

register_hook("mapnik_compile", "cascadenik_compile");
