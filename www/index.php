<?
Header("content-type: text/html; charset=UTF-8");
include("code.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenStreetBrowser</title>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<link rel="stylesheet" type="text/css" href="index.css" />
<script type="text/javascript" src="inc/hooks.js"></script>
<?
include "inc/global.php";
?>
<script type="text/javascript" src="index.js"></script>
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="lang/list.js"></script>
<!-- <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script> -->
</head>
<body>
<script type="text/javascript">
<?
unset($my_lat);

function maskErrors() {
}

if($text=file_get_contents("http://iplocationtools.com/ip_query.php?ip=$_SERVER[REMOTE_ADDR]")) {
  $dom=new DOMDocument();
  set_error_handler('maskErrors');
  $dom->loadXML($text);
  restore_error_handler();
  $my_lat=$dom->getElementsByTagName("Latitude")->item(0)->textContent;
  $my_lon=$dom->getElementsByTagName("Longitude")->item(0)->textContent;
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
<div class='logo'><a href="http://wiki.openstreetmap.org/wiki/OpenStreetBrowser"><img src="img/osb_logo.png" alt="OpenStreetBrowser" name="OpenStreetBrowser" border="0"/></a><p>OpenStreet <span class="bigger">Browser</span></p></div>
<div class='search'>
<input id='search' value='<?=lang("search_field")?>' onChange="search(this)" onFocus="search_focus(this)" />
</div>
<div id='details' class='info'>
<form id='details_content' class='details'>
<?
print list_template();
?>
</form>
</div>
<div id='lang_select'>
<a href='javascript:toggle_mapkey()'><?=lang("main:map_key")?></a> |
<a href='javascript:show_options()'><?=lang("main:options")?></a> |
<a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser'><?=lang("main:about")?></a> |
<a href='javascript:time_count_do_beg()'><?=lang("main:donate")?></a>
</div>
<?
//show_lang_select();
?>
<div class="map" id="map"></div>
<div class="shadow"></div>
<div class="map_key_hidden" id="map_key"></div>
<div class="licence"><?=lang("main:licence")?></div>
<?
call_hooks("html_done", null);
?>
<div class="permalink"><a href="" id="permalink"><?=lang("main:permalink")?></a></div>
</body>
</html>
