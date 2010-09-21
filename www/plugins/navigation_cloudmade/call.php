<?
include "../../../conf.php";

$valid_cm_param=array("lang", "units");
$param=$_REQUEST;
$start_point=$param['start_point'];
unset($param['start_point']);
$end_point=$param['end_point'];
unset($param['end_point']);
if(!$param['route_type'])
  $route_type="car";
if($param['route_type_modifier'])
  $route_type.="/{$param['route_type_modifier']}";
unset($param['route_type']);
unset($param['route_type_modifier']);
unset($param['output_format']);

$add_param=array();
foreach($param as $k=>$v) {
  if(in_array($k, $valid_cm_param))
    $add_param[]="$k=$v";
}

$url="http://routes.cloudmade.com/$key_cloudmade_api/api/0.3/".
     "$start_point,$end_point/$route_type.gpx?".implode("&", $add_param);

Header("Content-Type: text/xml; charset=UTF-8");
print file_get_contents($url);
