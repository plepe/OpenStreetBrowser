<?
# Necessary include files
require_once("list.php");

# registering
register_list("places", "streets", "infolist_streets");

class infolist_streets extends infolist {
  function show_info($bounds) {
    global $SRID;
    global $load_xml;

    $network=array();
    if($bounds[zoom]>13) {
      $network[]="'urban'";
      $network[]="'local'";
      $network[]="''";
    }
    else
      $ret.="<i>Zoom in for more streets</i><br />";
    if($bounds[zoom]>11) {
      $network[]="'region'";
    }
    if($bounds[zoom]>10) {
      $network[]="'national'";
    }
    $network[]="'international'";

    $qry="(select 'coll' as element, planet_osm_streets.osm_id as id from planet_osm_streets left join planet_osm_colls on planet_osm_streets.osm_id=planet_osm_colls.id where network in (".implode(",", $network).") and way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and Intersects(way, PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID)))";
    debug($qry);

    $res=sql_query($qry);
    $list=array();
    while($elem=pg_fetch_assoc($res)) {
      $load_list[]=$elem;
    }
    load_objects($load_list);
    foreach($load_list as $elem) {
      $x=load_object($elem);
      $list[$x->long_name()][]=$x;
    }

    $list_s=array_keys($list);
    natsort($list_s);

    foreach($list_s as $k) {
      $x=$list[$k];
      foreach($list[$k] as $x) {
	$ret.=list_entry($x->id, $x->long_name());
	$load_xml[]=$x->id;
      }
    }

    return $ret;
  }

}
