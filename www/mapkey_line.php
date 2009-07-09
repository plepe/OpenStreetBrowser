<?
Header("content-type: image/svg+xml");
print '<?xml version="1.0" encoding="utf-8" ?>';
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
 "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="30" height="12"
 xmlns="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 style="background: #f2efd9"
 >
 <title>Polygon Symbolizer for Map Key</title>
 <style type="text/css"><![CDATA[
  text {font-size:60px; text-anchor:middle;}
 ]]></style>
 <defs>
<?
$pattern=array();
$css="";
$horiz=0;
foreach($_REQUEST[param] as $v) {
//  if($k=="stroke")
//    $v="#$v";
//  $css.="$k: $v; ";
  $css="";
  $show=false;
  foreach($v as $s_k=>$s_v) {
    $s_v=stripslashes($s_v);
    switch($s_k) {
      case "line-width": $css[]="stroke-width: $s_v"; $show=true; break;
      case "line-color": $css[]="stroke: $s_v"; break;
      case "line-pattern-file": 
        $css[]="stroke: url(#pattern".sizeof($pattern).")"; $show=true; 
        $pattern[]=$s_v;
	$horiz=1;
	break;
      case "line-dasharray": $css[]="stroke-dasharray: $s_v"; break;
      case "line-cap": $css[]="stroke-linecap: $s_v"; break;
      case "line-join": $css[]="stroke-linejoin: $s_v"; break;
    }
  }

  if($show) {
    $list_css[]=implode("; ", $css);
  }
}

foreach($pattern as $i=>$p) {
  if(eregi("url\(\'(.*)\'\)", $p, $m)) {
    $p=$m[1];
    $s=getimagesize("render_$p");
    print "<pattern id='pattern$i' width='$s[0]' height='$s[1]' patternUnits='userSpaceOnUse'><image width='$s[0]' height='$s[1]' xlink:href='render_$p' /></pattern>\n";
  }
}
?>
</defs>
<?
foreach($list_css as $css) {
  if($horiz)
    print "<line x1='4' y1='6' x2='38' y2='6' style=\"$css;\" />\n";
  else
    print "<line x1='4' y1='4' x2='38' y2='15' style=\"$css;\" />\n";
}
?>
</svg>

