<?
function cascadenik_svg_process($infile, $outfile, $path) {
  $f1=fopen("$path/$infile", "r");
  $f2=fopen("$path/$outfile", "w");

  while($r=fgets($f1)) {
    while(preg_match("/^(.*)url\(\'(.*)\.svg\'\)(.*)$/", $r, $m)) {
      system("convert -background none $path/$m[2].svg $path/$m[2].cascadenik_svg.png");
      $r="$m[1]url('$m[2].cascadenik_svg.png')$m[3]";
    }

    fwrite($f2, $r);
  }

  fclose($f1);
  fclose($f2);
}

function cascadenik_svg_compile($file) {
  $nfile="$file.cascadenik_svg.mml";
  if(!preg_match("/^(.*\/)[^\/]*$/", $file, $m)) {
    print "Mapnik Rotate: No path found???\n";
    return;
  }
  $path=$m[1];

  $dom=new DOMDocument();
  $dom->load($file);

  $list=$dom->getElementsByTagName("Stylesheet");
  for($i=0; $i<$list->length; $i++) {
    print "$i\n";
    $mss_file=$list->item($i)->getAttribute("src");
    cascadenik_svg_process($mss_file, $mss_file.".cascadenik_svg", $path);
    $list->item($i)->setAttribute("src", $mss_file.".cascadenik_svg");
  }

  $dom->save($nfile);

  $file=$nfile;
}

register_hook("cascadenik_compile", "cascadenik_svg_compile");
