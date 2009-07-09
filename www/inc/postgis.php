<?
function bbox($way) {
  $p=strpos($way, "(");
  $type=substr($way, 0, $p);
  $coord=substr($way, $p+1, strlen($way)-$p-2);

  switch($type) {
    case "LINESTRING":
    case "POINT":
      $coordlist=explode(",", $coord);
      $bbox=0;
      foreach($coordlist as $c) {
        $cp=explode(" ", $c);
	if(!$bbox)
	  $bbox=array($cp[0], $cp[1], $cp[0], $cp[1]);
	else {
	  if($cp[0]<$bbox[0]) $bbox[0]=$cp[0];
	  if($cp[1]<$bbox[1]) $bbox[1]=$cp[1];
	  if($cp[0]>$bbox[2]) $bbox[2]=$cp[0];
	  if($cp[1]>$bbox[3]) $bbox[3]=$cp[1];
	}
      }
      break;
  }

  return $bbox;
}

function in_bounds($object, $bounds=0) {
  if(!$bounds)
    return true;

  $b=bbox($object[0]->data[way]);

  if(($bounds[right]<$b[0])||
     ($bounds[top]<$b[1])||
     ($bounds[left]>$b[2])||
     ($bounds[bottom]>$b[3]))
    return false;
  return true;
}

function postgis_collect($geo1, $geo2) {
  return "GEOMETRYCOLLECTION($geo1,$geo2)";
}
