<?
Header("content-type: text/html; charset=UTF-8");
include("code.php");

if($_SERVER['QUERY_STRING']!="") {
  $path=$_SERVER['SCRIPT_NAME'];
  if(preg_match("/^(.*)index.php$/", $path, $m))
    $path=$m[1];

  Header("Location: {$path}#?{$_SERVER['QUERY_STRING']}");
}

if(!$version) {
	$version="dev";
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenStreetBrowser</title>
<script src="OpenLayers/OpenLayers.js"></script>
<link rel="stylesheet" type="text/css" href="index.css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<link rel="search" type="application/opensearchdescription+xml" title="OpenStreetBrowser" href="osb_search.xml" />
<script type="text/javascript" src="inc/hooks.js"></script>
<?
include "inc/global.php";
call_hooks("init", $dummy);
print_add_html_headers();
?>
<script type="text/javascript" src="index.js"></script>
<script type="text/javascript" src="ajax.js"></script>
<!-- <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script> -->
</head>
<body>
<?
call_hooks("html_start");
?>
<div id='content'>
<script type="text/javascript">
<?
unset($my_lat);

function maskErrors() {
}

$first_load=1;
$mlat=$_REQUEST[mlat];
$mlon=$_REQUEST[mlon];
if($_REQUEST[lon]) {
  $lon=$_REQUEST[lon];
  $lat=$_REQUEST[lat];
  $zoom=$_REQUEST[zoom];
  $first_load=0;
}
elseif($_REQUEST[mlon]) {
  if(!$lon) {
    $lon=$mlon;
    $lat=$mlat;
    $zoom=$_REQUEST[zoom];
  }
}
else {
  if(isset($my_lat)) {
    $lat=$my_lat;
    $lon=$my_lon;
    $zoom=12;
  }
  else {
    $lon=18.83461;
    $lat=52.41508;
    $zoom=4;
  }
}
?>

var start_zoom=<?=$zoom?>;
var start_lon=<?=$lon?>;
var start_lat=<?=$lat?>;
var first_load=<?=$first_load?>;
<?
if(isset($mlon))
  print "var marker_pos={ lon: $mlon, lat: $mlat };\n";
else
  print "var marker_pos=null;\n";
if(isset($my_lat))
  print "var my_pos={ lon: $my_lon, lat: $my_lat };\n";
else
  print "var my_pos=null;\n";
?>
</script>

<div class='menu'>
<?
$menu_list=array();
$menu_list[]=array(-10, "<div class='logo'><a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser'><img src='img/osb_logo.png' alt='OpenStreetBrowser' name='OpenStreetBrowser' border='0'/></a><p>OpenStreet <span class='bigger'>Browser</span><br/><span class='version'><a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/ChangeLog' target='_new'>{$version}</a></span></p></div>");
$menu_list[]=array(0,
  "<div id='details' class='info' style='top:150px'>\n".
  "<form id='details_content' class='details' action='javascript:details_content_submit()'>\n".
  list_template().
  "</form></div>\n");

$main_links=array(
  array(0, "<a href='javascript:show_options()'>".lang("main:options")."</a>"),
  array(1, "<a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser'>".lang("main:about")."</a>"),
  array(5, "<a href='javascript:time_count_do_beg()'>".lang("main:donate")."</a>")
);
call_hooks("main_links", &$main_links);
$main_links=weight_sort($main_links);
$main_links=implode(" |\n", $main_links);

$menu_list[]=array(5,
  "<div id='options'>\n".
  "<div id='user_info'>{$current_user->login_info()}</div>\n".
  "<div id='main_links'>{$main_links}</div>\n".
  "</div>\n");

call_hooks("menu_show", &$menu_list);

$menu_list=weight_sort($menu_list);
foreach($menu_list as $entry) {
  print $entry;
}

?>
<?
//show_lang_select();
?>
<div class="map" id="map"></div>

<div class="shadow"></div>
<div id="licence"><?=lang("main:licence")?></div>
<?
call_hooks("html_done", null);
?>
<div class="permalink"><a href="" id="permalink" onclick="var center=map.getCenter().transform(map.getProjectionObject(), new OpenLayers.Projection('EPSG:4326'));"><?=lang("main:permalink")?></a></div>
</div><!-- #content -->
<?
call_hooks("html_end", null);
?>
</body>
</html>
