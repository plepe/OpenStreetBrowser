<?
# Necessary include files
require_once("object.php");

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

# the object for displaying
class object_place extends object {
  function long_name() {
    $ret=parent::long_name();

    $ret.=" (".lang("place_".$this->data[tags]->get("place")).")";

    return $ret;
  }

}
