<? /*

http://..../list.php?[options]

options:
  viewbox=left,top,right,bottom
  zoom=12
  category=culture/religion,gastro
  srs=900913
  exclude=node_123456,way_12345,...
  ui_lang=en
  data_lang=
  count=10

example: 
  http://.../list.php?viewbox=1820510.3841097,6140479.7509884,1821443.1547203,6139601.9194918&zoom=17&category=gastro&ui_lang=en
*/
$design_hidden=1;
include "code.php";
include "inc/sql.php";
include "inc/debug.php";
include "inc/tags.php";
include "inc/object.php";
include "inc/categories.php";
include "inc/lock.php";
include "inc/category.php";
include "inc/postgis.php";
include "inc/global.php";

$importance=array("international", "national", "regional", "urban", "suburban", "local");

$lang="en";
if($_REQUEST[lang])
  $lang=$_REQUEST[lang];

$ret=main();
Header("content-type: text/xml; charset=utf-8");
print $ret;

function main() {
  global $lists_dir;
  $ret ="<?xml version='1.0' encoding='UTF-8'?>\n";
  $ret.="<results generator='OpenStreetBrowser'>\n";

  $ret.="<request";
  foreach($_REQUEST as $rk=>$rv) {
    if(in_array($rk, array("viewbox", "zoom", "category", "ui_lang", "data_lang"))) {
      $ret.=" $rk=\"".htmlentities(stripslashes($rv))."\"";
    }
  }
  $ret.="/>\n";

  $r=$_REQUEST;
  if($r[category]) {
    $cs=explode(",", $r[category]);
    foreach($cs as $c) {
      unset($load_cat);
      // This is a custom list

      $cat=new category($c);
      $ret.=$cat->get_list($r);
    }
  }

  $ret.="</results>\n";

  return $ret;
}
