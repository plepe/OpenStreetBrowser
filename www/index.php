<?
/**
 * @file index.php
 * @brief The main file which is called on load and outputs the application.
 */
Header("content-type: text/html; charset=UTF-8");
include("code.php");

if(!isset($version_string)) {
  exec("git rev-parse --short HEAD", $version_string, $status);
  if($status==0) {
    if(!isset($version))
      $version="rev-{$version_string[0]}";
    $version_string="?{$version_string[0]}";
  }
  else
    $version_string="";
}

if(!isset($version))
  $version="";

call_hooks("init", $dummy);
call_hooks("http_head");

// check for installed OpenLayers instance
if(!file_exists("lib/OpenLayers/OpenLayers.js")) {
  print "Please download <a href='http://openlayers.org/'>OpenLayers</a> and extract to www/lib/OpenLayers/\n";
  exit();
}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenStreetBrowser</title>
<script src="lib/OpenLayers/OpenLayers.js<?=$version_string?>"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" />
<link rel="stylesheet" type="text/css" href="index.css<?=$version_string?>" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<link rel="search" type="application/opensearchdescription+xml" title="OpenStreetBrowser" href="osb_search.xml" />
<script type="text/javascript" src="inc/hooks.js<?=$version_string?>"></script>
<script type="text/javascript" src="inc/lang.js<?=$version_string?>"></script>
<?
include "inc/global.php";
call_hooks("init", $dummy);
print_add_html_headers();
call_hooks("html_head", $dummy);
?>
<script type="text/javascript" src="index.js<?=$version_string?>"></script>
<script type="text/javascript" src="ajax.js<?=$version_string?>"></script>
<!-- <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script> -->
<link rel="stylesheet" type="text/css" href="screen_adapt.css<?=$version_string?>" />
</head>
<body>
<?
call_hooks("html_start");
?>
<script type="text/javascript">
<?
unset($my_lat);

function maskErrors() {
}

$first_load=1;
$start_location=$default_location;

if(isset($_REQUEST['mlon'])) {
  $mlat=$_REQUEST['mlat'];
  $mlon=$_REQUEST['mlon'];
}
if(isset($_REQUEST['lon'])) {
  $start_location['lon']=$_REQUEST['lon'];
  $start_location['lat']=$_REQUEST['lat'];

  $first_load=0;
}
elseif(isset($_REQUEST['mlon'])) {
  $start_location['lon']=$mlon;
  $start_location['lat']=$mlat;
}
else {
  if(isset($my_lat)) {
    $start_location['lat']=$my_lat;
    $start_location['lon']=$my_lon;
    $start_location['zoom']=12;
  }
}

if(isset($_REQUEST['zoom']))
  $start_location['zoom']=$_REQUEST['zoom'];

html_export_var(array("start_location"=>$start_location, "first_load"=>$first_load));

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

<div id='content'>
<div id='sidebar_container'>
<div id='sidebar'>
<div id='logo'><div id='logo_image'><a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser'><img src='img/osb_logo.png' alt='OpenStreetBrowser' name='OpenStreetBrowser' border='0'/></a></div><div id='logo_name'>OpenStreet<span id='logo_name_bigger'>Browser</span><div id='version'><a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/ChangeLog' target='_new'><?=$version?></a></div></div></div>
<div id='content_container'>
<?
$menu_list=array();
$menu_list[]=array(0,
  "<div id='details_container'>\n".
  "<div id='details'>\n".
  "<form id='details_content' class='details' action='javascript:details_content_submit()'>\n".
  list_template().
  "</form></div></div>\n");

$main_links=array(
  array(0, "<a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/Instructions' target='_new'>".lang("main:help")."</a>"),
  array(1, "<a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser' target='_new'>".lang("main:about")."</a>"),
  array(5, "<a href='javascript:time_count_do_beg()'>".lang("main:donate")."</a>")
);
call_hooks("main_links", &$main_links);
$main_links=weight_sort($main_links);
$main_links=implode(" |\n", $main_links);

$menu_list[]=array(5,
  "<div id='menu'>\n".
  "<div id='user_info'>{$current_user->login_info()}</div>\n".
  "<div id='main_links'>{$main_links}</div>\n".
  "</div>\n");

call_hooks("menu_show", &$menu_list);

$menu_list=weight_sort($menu_list);
foreach($menu_list as $entry) {
  print $entry;
}

?>
</div> <!-- #content_container -->
</div> <!-- #sidebar -->
</div> <!-- #sidebar_container -->
<?
//show_lang_select();
?>

<div id='map_container'>
<div class="map" id="map">

<div class="shadow"></div>
<div id="licence"><?=lang("main:licence")?></div>
<?
call_hooks("html_done", null);
?>
<div class="permalink"><a href="" id="permalink" onclick="var center=map.getCenter().transform(map.getProjectionObject(), new OpenLayers.Projection('EPSG:4326'));"><?=lang("main:permalink")?></a></div>

</div> <!-- #map -->
</div> <!-- #map_container -->
</div> <!-- #content -->
<?
call_hooks("html_end", null);
?>
</body>
</html>
