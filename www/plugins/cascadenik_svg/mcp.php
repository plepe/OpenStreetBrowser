<?
function cascadenik_svg_process($infile, $outfile, $path) {
  global $tmp_dir;

  print "Cascadenik SVG $path/ $infile $tmp_dir/ $outfile\n";
  $f1=fopen("$path/$infile", "r");
  $f2=fopen("$tmp_dir/$outfile", "w");

  while($r=fgets($f1)) {
    if(preg_match("/^(.*)url\(\'(.*)\/([^\/]*)\.([a-zA-Z]{3})\'\)(.*)$/", $r, $m)) {
      if($m[4]=="svg") {
	print "Converting $m[2]/$m[3].svg to $m[3].png\n";
	copy("$path/$m[2]/$m[3].svg", "$tmp_dir/$m[3].svg");
	system("rsvg $tmp_dir/$m[3].svg $tmp_dir/$m[3].png");
	$r="$m[1]url('$tmp_dir/$m[3].png')$m[5]";
      }
      else {
	$r="$m[1]url('$path/$m[2]/$m[3].$m[4]')$m[5]";
      }
    }

    fwrite($f2, $r);
  }

  fclose($f1);
  fclose($f2);
}

function cascadenik_svg_compile($file, $path=null) {
  global $tmp_dir;

  if(!preg_match("/^(.*\/)([^\/]*)$/", $file, $m)) {
    print "Mapnik Rotate: No path found???\n";
    return;
  }
  if(!$path)
    $path=$m[1];
  $nfile="$tmp_dir/$m[2]";

  $dom=new DOMDocument();
  $dom->load($file);

  $list=$dom->getElementsByTagName("Stylesheet");
  for($i=0; $i<$list->length; $i++) {
    $mss_file=$list->item($i)->getAttribute("src");
    cascadenik_svg_process($mss_file, $mss_file, $path);
    $list->item($i)->setAttribute("src", "$tmp_dir/$mss_file");
  }

  $dom->save($nfile);

  $file=$nfile;
}

register_hook("cascadenik_compile", "cascadenik_svg_compile");
