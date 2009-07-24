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
if($_REQUEST[lon]) {
  $lon=$_REQUEST[lon];
  $lat=$_REQUEST[lat];
  $zoom=$_REQUEST[zoom];
  $first_load=0;
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
<div id='adv_search'>
<select name='search_type'>
  <option value='*'>*</option>
  <option value='amenity'>Amenity</option>
  <option value='type'>Type</option>
  <option value='shop'>Shop</option>
  <option value='place'>Place</option>
</select>
=
<select id='search_value' name='search_value'>
  <option value='*'>*</option>
</select><br>
<input type='radio' name='search_where' value='world'> everywhere
<input type='radio' name='search_where' value='part'> map section<br>
</div>
</div>
<div id='details' class='info'>
<div id='details_content' class='details'><img src="img/ajax_loader.gif" /> loading</div>
</div>
</div>
<div id='lang_select'>
<a href='javascript:toggle_mapkey()'>Map Key</a> | <!-- <a href='javascript:show_options()'>Options</a> | --> <a href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser'>About</a>
</div>
<?
//show_lang_select();
?>
<div class="map" id="map"></div>
<div class="shadow"></div>
<div class="map_key_hidden" id="map_key"></div>
<div class="licence">Map Data: <a href="http://creativecommons.org/licenses/by-sa/2.0/">cc-by-sa</a> <a href="http://www.openstreetmap.org">OpenStreetMap</a> contributors | OSB: <a href="http://wiki.openstreetmap.org/wiki/User:Skunk">Stephan Plepelits</a> and <a href="http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved">contributors</a></div>
<?
call_hooks("html_done", null);
?>
</body>
</html>
