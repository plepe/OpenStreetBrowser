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

// route_type
if(!$param['route_type'])
  $route_type="car";
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
     "$start_point,$end_point/$route_type.gpx?".implode("&", $add_param);

// get route
$ret=file_get_contents($url);

// print route as answer (in content-type text/xml)
Header("Content-Type: text/xml; charset=UTF-8");
print $ret;
