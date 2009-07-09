#!/usr/bin/php
<?
$sql=pg_connect("dbname=gis");// user=www password=cityrunner host=localhost");

$dom=new DOMDocument();
$dom->load("/osm/skunkosm/render/overlay_ch.xml");

//$box=array(1823787.494884305,6138504.867525934,1825010.487336868,6139727.859978498);
$box=array(1813797.75987783, 6130153.02441634, 1834395.04941202, 6151816.81425115);

$layers=$dom->getElementsByTagname("Layer");
foreach($layers as $l) {
  print "* Layer, class ".$l->getAttribute("name")."\n";
  $params=$l->getElementsByTagname("Parameter");
  foreach($params as $p) {
    if($p->getAttribute("name")=="table") {
      $time=time();
      $res=pg_query("select * from {$p->firstChild->data} where way && setSRID('BOX3D($box[0] $box[1],$box[2] $box[3])'::box3d,900913)");
      print "Time: ".(time()-$time)."s\n";
      print "Rows: ".pg_num_rows($res)."\n";
    }
  }
}
