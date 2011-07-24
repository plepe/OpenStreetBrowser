<?
include "../../../conf.php";

$valid_cm_param=array("lang", "units");
$param=$_REQUEST;

// Preprocess parameters
// start_point
$start_point=$param['start_point'];
$start_point="{$start_point['lat']},{$start_point['lon']}";
unset($param['start_point']);

// end_point
$end_point=$param['end_point'];
$end_point="{$end_point['lat']},{$end_point['lon']}";
unset($param['end_point']);

// transfer points
$transit_points=array();
if($param['transit_points']) foreach($param['transit_points'] as $p) {
  $transit_points[]="{$p['lat']},{$p['lon']}";
}
if(sizeof($transit_points))
  $transit_points="[".implode(",", $transit_points)."],";
else
  $transit_points="";

// route_type
if(!$param['route_type']) {
  $route_type="car";
} else {
  $route_type=$param['route_type'];
}
unset($param['route_type']);

// route_type_modifier
if($param['route_type_modifier'])
  $route_type.="/{$param['route_type_modifier']}";
unset($param['route_type_modifier']);

// output_format -> force gpx
unset($param['output_format']);

// pass the rest of the parameters
$add_param=array();
foreach($param as $k=>$v) {
  if(in_array($k, $valid_cm_param))
    $add_param[]="$k=$v";
}

// compile URL for the route
$url="http://routes.cloudmade.com/$key_cloudmade_api/api/0.3/".
     "$start_point,$transit_points$end_point/$route_type.gpx?".implode("&", $add_param);

// get route
@$ret=file_get_contents($url);
if($ret===false) {
  print("routes.cloudmade.com reported: $http_response_header[0]");
  exit;
}

// print route as answer (in content-type text/xml)
Header("Content-Type: text/xml; charset=UTF-8");
print $ret;
