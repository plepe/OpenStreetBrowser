<?
Header("content-type: text/html; charset=UTF-8");
include("code.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenStreetBrowser</title>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<link rel="stylesheet" type="text/css" href="index.css" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<link rel="search" type="application/opensearchdescription+xml" title="OpenStreetBrowser" href="osb_search.xml" />
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


<div id='toolboxbuttons'">
<table cellspacing="0" style="border:0px; margin:0px; padding:0px;">
	<tr>
		<td id="toolbox1" class="toolboxbutton"><a href='javascript:toolbox_map()'><img src="img/toolbox_map.png" border="0" title="set map position"/></a></td>
		<td id="toolbox2" class="toolboxbutton"><a href='javascript:toolbox_home()'><img src="img/toolbox_home.png" border="0" title="set home position"/></a></td>
		<td id="toolbox3" class="toolboxbutton"><a href='javascript:toolbox_favorites()'><img src="img/toolbox_favorites.png" border="0" title="set markers"/></a></td>
		<td id="toolbox4" class="toolboxbutton"><a href='javascript:toolbox_measure()'><img src="img/toolbox_measure.png" border="0" title="select measure tool"/></a></td>
	</tr>
</table>
</div>

<div id='toolbox' class='toolbox' style="display:block; position:absolute; top:140px; clip:rect(0px, 250px, 30px, 0px);"></div>
<div id='search' class='search' style="position:absolute; top:143px;">
<form name='osb_search_form_name' id='osb_search_form' action='javascript:search()'">
<input name='osb_search' id='search' style="border-color:#999999;" value='<?=lang("search_field")?>' onFocus="search_focus(this)" onkeyup="search_brush(this,event)" onblur="search_onblur(this)" "title="<?=lang("search_tip")?>"/>
<img name='brush' src="besen.png" border="0" alt="" title="<?=lang("search_clear")?>" style="position:absolute; right:3px; top:6px; visibility:hidden; cursor:pointer;" onclick="search_clear(document.osb_search_form_name.osb_search)" onmousedown="if (event.preventDefault) event.preventDefault()">
</form>
</div>
<div id='details' class='info' style="top:180px">
<form id='details_content' class='details' action='javascript:details_content_submit()'>
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
<div id="contextmenu" class="contextmenu" onmouseout="contextmenu_mouseout(event)" style="top:20px; left:500px; display:none;"><table>
<tr><td class="contextmenu_entry"><a href="#">set home</a></td></tr>
<tr><td class="contextmenu_entry"><a href="#">set destination</a></td></tr>
<tr><td class="contextmenu_entry"><a href="#">set favorite</a></td></tr>
<tr><td class="contextmenu_entry"><a href="#">show coordinates</a></td></tr></table></div>
<div class="shadow"></div>
<div class="map_key_hidden" id="map_key"></div>
<div class="licence"><?=lang("main:licence")?></div>
<?
call_hooks("html_done", null);
?>
<div class="permalink"><a href="" id="permalink" onclick="var center=map.getCenter().transform(map.getProjectionObject(), new OpenLayers.Projection('EPSG:4326'));cookie_write('_osb_permalink', center.lon + '|' + center.lat + '|' + map.zoom + '|' + location.hash);"><?=lang("main:permalink")?></a></div>
<script type="text/javascript">toolbox_init();</script>
</body>
</html>
