<?
# Necessary include files
require_once("object.php");
require_once("list.php");

# important vars and constants
$place_list=array(
  "country"  =>array("zoom"=>4),
  "state"    =>array("zoom"=>6),
  "city"     =>array("zoom"=>6),
  "town"     =>array("zoom"=>8),
  "region"   =>array("zoom"=>8),
  "village"  =>array("zoom"=>11),
  "county"   =>array("zoom"=>11),
  "hamlet"   =>array("zoom"=>12),
  "suburb"   =>array("zoom"=>12),
  "island"   =>array("zoom"=>8)
);

# registering
foreach($place_list as $type=>$defs) {
  register_object_type("place", $type, "object_place");
}
register_list("places", "places", "infolist_places");

# the object for displaying
class object_place extends object {
  function long_name() {
    $ret=parent::long_name();

    $ret.=" (".lang("place_".$this->data[tags]->get("place")).")";

    return $ret;
  }

}

# and the list
class infolist_places extends infolist {
  function title() {
    return "Places";
  }

  function show_info($bounds) {
    global $SRID;
    global $place_list;
    global $load_xml;

    $sql_place_list=array();

    foreach($place_list as $type=>$defs) {
      if($bounds[zoom]>=$defs[zoom]) {
	$sql_place_list[]=$type;
      }
    }

    $sql_place_list="(place='".implode("' or place='", $sql_place_list)."')";

    $qry="select 'node' as element, id, lon, lat, astext(way) as way, tags from planet_osm_point join planet_osm_nodes on osm_id=id where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and $sql_place_list order by (CASE WHEN place='country' THEN 0 WHEN place='state' THEN 1 WHEN place='city' THEN 2 WHEN place='region' THEN 3 WHEN place='town' THEN 4 ELSE 10 END), name";

    $res=sql_query($qry);
    $list=array();
    while($elem=pg_fetch_assoc($res)) {
      //$list[]=$elem;
      $x=load_object($elem);
      $ret.=list_entry($x->id, $x->long_name());
      $load_xml[]=$x->id;
    }

    return $ret;
  }
}
