#!/usr/bin/php
<?
$steps=8;
$list=array(
//  "crossing"=>array("zoom"=>12),
  "mountain_pass"=>array("zoom"=>12)
);

$f=fopen("rot_feature.mss", "w");

foreach($list as $l=>$opt) {
  for($i=0; $i<8; $i++) {
    system("convert -background none -rotate ".(($i-$steps/2)*(180.0/$steps))." img/src/$l.svg img/{$l}_{$i}.png");

    fputs($f, ".rot_feature[type=$l][rotate=$i][zoom>={$opt[zoom]}] {\n");
    fputs($f, "  point-file: url('img/{$l}_{$i}.png');\n");
    fputs($f, "}\n");
  }
}

$steps=72;
$l="stop";
for($i=0; $i<$steps; $i++) {
  $is=sprintf("%02d", $i);
  system("convert -background none -rotate ".(90+$i/$steps*360)." img/src/$l.svg img/{$l}_{$is}.png");
}
