<?
function goole_to_utm($pos) {
  if(!$pos)
    return;
  if((!$pos[lon])||(!$pos[lat]))
    return;
  $f=popen("echo \"$pos[lon] $pos[lat]\" | cs2cs +proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs +to +proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs -f '%f'", "r");
  $r=fgets($f);
  eregi("^([0-9\.]*)\t([0-9\.]*)", $r, $m);
  //$res=sql_query("select astext(Transform(PointFromText('POINT($pos[lon] $pos[lat])', 900913), 4326)) as pos");
//  if($elem=pg_fetch_assoc($res)) {
//    print_r($elem);
//    ereg("POINT\(([0-9\.]*) ([0-9\.]*)\)", $elem["pos"], $m);
    return array("lon"=>$m[1], "lat"=>$m[2]);
//  }
}
