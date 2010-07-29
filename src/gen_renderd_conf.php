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

  $file="/home/osm/data/inc_renderd.conf";
  if(file_exists($file)) {
    fwrite($conf, file_get_contents($file));
  }

  // generate dummy entry in renderd.conf to avoid renderd-bug
  global $data_path;
  fwrite($conf, "[dummy]\n");
  fwrite($conf, "URI=/tiles/dummy/\n");
  fwrite($conf, "XML=/home/osm/data/render_dummy.xml\n");
  fwrite($conf, "HOST=dummy.host\n");

  fclose($conf);
}
