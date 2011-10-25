<?
function renderd_register(&$renderd, $id, $file) {
  $renderd.="[$id]\n";
  $renderd.="URI=/tiles/$id/\n";
  $renderd.="XML=$file\n";
  $renderd.="HOST=dummy.host\n";
  $renderd.="\n";
}

function gen_renderd_conf() {
  global $root_path;
  global $lists_dir;

  $conf=fopen("$root_path/data/renderd.conf", "w");

  $template=file_get_contents("$root_path/src/renderd.conf.template");
  fwrite($conf, $template);

  if(file_exists("$root_path/data/renderd.conf.local")) {
    $template=file_get_contents("$root_path/data/renderd.conf.local");
    fwrite($conf, $template);
  }

  foreach(category_list() as $f=>$tags) {
    print "check state of category '$f'\n";
    $category=new category($f);
    $cat_version=$category->get_newest_version();
    $recompile=false;

    if(!file_exists("$lists_dir/$f.renderd")) {
      $recompile=true;
    }
    else {
      $c=$category->get_renderd_config();

      if((!isset($c['VERSION']))||($cat_version!=$c['VERSION']))
	$recompile=true;
    }

    if($recompile) {
      print "  (re-)compiling $f\n";
      $category->compile();
    }

    $conf_part=file_get_contents("$lists_dir/$f.renderd");
    fwrite($conf, $conf_part);
  }

  global $renderd_files;
  if($renderd_files) foreach($renderd_files as $file) {
    if(file_exists($file)) {
      fwrite($conf, file_get_contents($file));
    }
  }
  
  $renderd="";
  call_hooks("build_renderd", &$renderd);
  fwrite($conf, $renderd);

  // generate dummy entry in renderd.conf to avoid renderd-bug
  global $data_path;
  fwrite($conf, "[dummy]\n");
  fwrite($conf, "URI=/tiles/dummy/\n");
  fwrite($conf, "XML=/home/osm/data/render_dummy.xml\n");
  fwrite($conf, "HOST=dummy.host\n");

  fclose($conf);
}
