<?
function gen_renderd_conf() {
  global $root_path;
  global $lists_dir;

  $conf=fopen("$root_path/data/renderd.conf", "w");

  $template=file_get_contents("$root_path/src/renderd.conf.template");
  fwrite($conf, $template);

  $d=opendir("$lists_dir");
  while($f=readdir($d)) {
    if(preg_match("/(.*)\.xml$/", $f, $m)) {
      if(!file_exists("$lists_dir/$f.renderd")) {
	print "compiling $m[1]\n";
	$x=new category($m[1]);
	$x->compile();
      }

      $conf_part=file_get_contents("$lists_dir/$f.renderd");
      fwrite($conf, $conf_part);
    }
  }
  closedir($d);

  fclose($conf);
}
