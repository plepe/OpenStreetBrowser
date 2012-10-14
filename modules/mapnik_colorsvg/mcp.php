<?
function mapnik_colorsvg_rule($rule, $filter) {
//  if(preg_match("/(.*)not \(\(\[(angle[:_a-zA-Z0-9]*)\]=([0-9]*)\)\)(.*)/", $filter->nodeValue, $m)) {
//  }
  $color_replace=array();
  while(preg_match("/^(.*)'(.*)_color_([0-9A-Fa-f]{6})_([0-9A-Fa-f]{6})'(.*)$/", $filter->nodeValue, $m)) {
    if(substr($m[1], -13)!="not (([type]=")
      $color_replace["#$m[3]"]="#$m[4]";
    $filter->nodeValue="$m[1]'$m[2]'$m[5]";
  }

  if(!sizeof($color_replace))
    return;

  $p=$rule->getElementsByTagName("PointSymbolizer");
  $p=$p->item(0);

  $file=substr($p->getAttribute("file"), 0, -4);

  $nfile="{$file}_".implode("_", $color_replace);
  if(!file_exists($nfile.".svg")) {
    $r=file_get_contents($file.".svg");
    $r=strtr($r, $color_replace);
    file_put_contents($nfile.".svg", $r);
    system("rsvg $nfile.svg $nfile.png");
  }

  $p->setAttribute("file", $nfile.".png");
  $p->setAttribute("type", "png");
}

function mapnik_colorsvg_process($file) {
  $dom=new DOMDocument();
  $dom->load($file);
   
  print "Starting Mapnik ColorSVG\n";
  $list=array();
  $filters=$dom->getElementsByTagName("Filter");
  for($i=0; $i<$filters->length; $i++) {
    $filter=$filters->item($i);

    $list[]=$filter;
  }

  foreach($list as $filter) {
    $rule=$filter->parentNode;

    mapnik_colorsvg_rule($rule, $filter);
  }

  $dom->save($file);

  return;
}
