#!/usr/bin/php
<?
foreach(array("forward", "backward") as $dir) {
  $base    =file_get_contents("img/src/arrowhead_$dir.svg");
  foreach(array("tram"=>"#ff0000", "bus"=>"#0000ff", "tram_bus"=>"#be007f", "subway"=>"#d4009f", "ferry"=>"#00ffff", "rail"=>"#202020") as $type=>$color) {
    $r=array("#000000"=>$color);
    $f=strtr($base, $r);

    $file=fopen("/tmp/x.svg", "w");
    fwrite($file, $f);
    fclose($file);

    system("convert -background none /tmp/x.svg img/{$type}_{$dir}.png");
  }
}


exit;
$base    =file_get_contents("template_arrow.svg");

function fit($forward, $backward, $color, $pos, $dash="") {
global $base;

  $r=array("%STROKE%"=>$color, "%DASH%"=>$dash, 
           "%WIDTH%"=>$pos[0], "%HEIGHT%"=>$pos[1],
           "%POSF%"=>$pos[2], "%POSB%"=>$pos[3]);
  $forward=strtr($forward, $r);
  $backward=strtr($backward, $r);

  $r["%FORWARD%"]=$forward;
  $r["%BACKWARD%"]=$backward;
  return strtr($base, $r);
}

$forward1='<path d="M 0 %POSF% L 30 %POSF%" '.
        'fill="none" stroke="%STROKE%" stroke-width="1" '.
        'marker-end="url(#TriangleE)" stroke-dasharray="%DASH%" />'.
        '<path d="M 30 %POSF% L 50 %POSF%" '.
        'fill="none" stroke="%STROKE%" stroke-width="1"  '.
        'stroke-dasharray="%DASH%" />';
$backward1='<path d="M 20 %POSB% L 50 %POSB%" '.
        'fill="none" stroke="%STROKE%" stroke-width="1" '.
        'marker-start="url(#TriangleB)" stroke-dasharray="%DASH%" />'.
        '<path d="M 0 %POSB% L 20 %POSB%" '.
        'fill="none" stroke="%STROKE%" stroke-width="1"  '.
        'stroke-dasharray="%DASH%" />';

foreach(array("tram"=>"#ff0000", "bus"=>"#0000ff") as $modal=>$color) {
  foreach(array("single"=>array(50, 9, 5, 5), "double"=>array(50, 10, 6.5, 3.5)) as $tracks=>$pos) {
    foreach(array(1, 2, 3) as $dir) {
      $f=""; $b="";
      print $dir&2;
      if($dir&1)
	$f=$forward1;
      if($dir&2)
	$b=$backward1;
      $dir_name=array(1=>"forward", "backward", "both");
      $dir_name=$dir_name[$dir];

      $file=fopen("/tmp/x.svg", "w");
      fwrite($file, fit($f, $b, $color, $pos, ""));
      fclose($file);

      system("convert -background none /tmp/x.svg img/{$modal}_{$tracks}_{$dir_name}.png");

      $file=fopen("/tmp/x.svg", "w");
      fwrite($file, fit($f, $b, $color, $pos, "5, 5"));
      fclose($file);

      system("convert -background none /tmp/x.svg img/{$modal}_tunnel_{$tracks}_{$dir_name}.png");
    }
  }
}
