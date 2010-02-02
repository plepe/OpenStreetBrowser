<? /*

http://..../list.php?[options]

options:
  viewbox=left,top,right,bottom
  zoom=12
  category=cat
  srs=900913
  exclude=node_123456,way_12345,...
  lang=en

example: 
  http://.../list.php?viewbox=1820510.3841097,6140479.7509884,1821443.1547203,6139601.9194918&zoom=17&category=gastro&lang=en
*/
$ret=main();
Header("content-type: text/xml; charset=utf-8");
print $ret;

function main() {
  $ret ="<?xml version='1.0' encoding='UTF-8'?>\n";
  $ret.="<results generator='OpenStreetBrowser'>\n";
  $ret.="</results>\n";

  return $ret;
}
