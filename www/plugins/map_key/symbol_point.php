<?
include "../../../conf.php";
Header("content-type: image/svg+xml");
print '<?xml version="1.0" encoding="utf-8" ?>';
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
 "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="30" height="30"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 style="background: #f2efd9"
 >
 <title>Point Symbolizer for Map Key</title>
 <style type="text/css"><![CDATA[
  text {font-size:60px; text-anchor:middle;}
 ]]></style>
 <defs>
<?
$list_css=array();
$list_dy=array();
$shift=0;
foreach($_REQUEST[param] as $v) {
//  if($k=="stroke")
//    $v="#$v";
//  $css.="$k: $v; ";
  $css="";
  $dy=0;
  $point=0;
  $show=array();
  foreach($v as $s_k=>$s_v) {
    $s_v=stripslashes($s_v);
    switch($s_k) {
      case "text-size":
        $css[]="font-size: ${s_v}px"; $show[text]=true; 
	$dy+=$s_v;
	break;
      case "text-fill": $css[]="fill: $s_v"; break;
      case "text-dy": 
        $dy+=$s_v;
	if($s_v<0)
	  $shift-=$s_v+$v["text-size"]*0.6;
	break;
      case "point-file": 
	$show[point]=true;
	$s=getimagesize("$root_path/www/$s_v");
	$point=array($p, $s[0], $s[1]);
	if($s[1]/2>$shift)
	  $shift=$s[1]/2;
	break;
      case "text-face-name": 
	switch($s_v) {
	  case "DejaVu Sans Book":
	    $css[]="font-family: $s_v, sans"; break;
	  case "DejaVu Sans Bold":
	    $css[]="font-family: $s_v, sans"; 
	    $css[]="font-weight: bold"; break;
	}
	break;
      case "line-cap": $css[]="stroke-linecap: $s_v"; break;
      case "line-join": $css[]="stroke-linejoin: $s_v"; break;
    }
  }

  if($show) {
    $list_css[]=implode("; ", $css);
    $list_dy[]=$dy;
    $list_show[]=$show;
    $list_point[]=$point;
  }
}

?>
</defs>
<?
foreach($list_show as $i=>$show) {
  $dy=$list_dy[$i];
  $css=$list_css[$i];
  $point=$list_point[$i];

  if($show[text])
    print "<text x='15' y='".($shift+$dy)."' style=\"$css;\">Text</text>\n";
  if($show[point]) {
    print "<image x='".round(15-$point[1]/2)."' y='".round($shift)."' width='$point[1]' height='$point[2]' xlink:href='$www_path/$point[0]' />\n";
  }
}
?>
</svg>

