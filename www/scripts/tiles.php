<?
include "../../conf.php";
?>
<html>
<head>
</head>
<body>
<form action='tiles.php' method='get'>
Paste range (e.g. '10 632-635 352-355') here:
<input name='range' value=''>
</form>

<?
if($_REQUEST['range']) {
  if(preg_match("/^([0-9]+) ([0-9]+)(-([0-9]+))? ([0-9]+)(-([0-9]+))?$/", $_REQUEST['range'], $m)) {
    $zoom=$m[1];
    $x1=$m[2];
    if(!($x2=$m[4]))
      $x2=$x1;
    $y1=$m[5];
    if(!($y2=$m[7]))
      $y2=$y1;
  }

  print "zoom: $zoom x: $x1-$x2 y: $y1-$y2\n";

  print "<div style='white-space: nowrap;'>\n";
  for($y=$y1; $y<=$y2; $y++) {
    for($x=$x1; $x<=$x2; $x++) {
      print "<img src='/tiles/basemap_base/$zoom/$x/$y.png' width='256' height='256'>";
    }
    print "<br/>";
  }
  print "</div>\n";
}
?>
</body>
</html>

