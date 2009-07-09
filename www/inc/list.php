<?
require_once("overlays.php");
require_once("layout.php");

$infolist_directory=array(
  "leisure_sport_tourism"=>array(
    "food_drink"=>array(
      "biergarten"=>array("type"=>"amenity", "subtype"=>"cuisine", "minzoom"=>11),
      "restaurant"=>array("type"=>"amenity", "subtype"=>"cuisine", "minzoom"=>11),
      "fast_food"=>array("type"=>"amenity", "subtype"=>"cuisine", "minzoom"=>11),
      "cafe"=>array("type"=>"amenity", "subtype"=>"cuisine", "minzoom"=>11),
      "vending_machine"=>array("type"=>"amenity", "subtype"=>"vending", "minzoom"=>11),
      "pub"=>array("type"=>"amenity", "subtype"=>"cuisine", "minzoom"=>11),
      "bar"=>array("type"=>"amenity", "minzoom"=>11),
      "nightclub"=>array("type"=>"amenity", "minzoom"=>11),
      "casino"=>array("type"=>"amenity", "minzoom"=>11)
    ),
    "cycle_hiking"=>array(
      "bicycle"=>array("type"=>"shop"),
      "outdoor"=>array("type"=>"shop"),
      "bicycle_rental"=>array("type"=>"amenity"),
      "bicycle_parking"=>array("type"=>"amenity"),
      "shelter"=>array("type"=>"amenity"),
      "drinking_water"=>array("type"=>"amenity"),
      "signpost"=>array("type"=>"amenity"),
      "alpine_hut"=>array("type"=>"amenity"),
      "alpine_hut"=>array("type"=>"tourism"),
      "mountain_hut"=>array("type"=>"amenity"),
      "picnic_site"=>array("type"=>"tourism"),
      "viewpoint"=>array("type"=>"tourism")
    ),
    "tourism"=>array(
      "hotel"=>array("type"=>"tourism", "name"=>"Hotels", "zoom"=>"12"),
      "motel"=>array("type"=>"tourism", "name"=>"Motels", "zoom"=>"12"),
      "guest_house"=>array("type"=>"tourism", "name"=>"Guest Houses", "zoom"=>"13"),
      "hostel"=>array("type"=>"tourism", "name"=>"Hostels", "zoom"=>"13"),
      "chalet"=>array("type"=>"tourism", "name"=>"Chalets", "zoom"=>"13"),
      "camp_site"=>array("type"=>"tourism", "name"=>"Camp Sites", "zoom"=>"12"),
      "caravan_site"=>array("type"=>"tourism", "name"=>"Caravan Sites", "zoom"=>"12"),
      "information"=>array("type"=>"tourism", "name"=>"Tourist Informations", "zoom"=>"13")
    ),
    "attractions"=>array(
      "theme_park"=>array("type"=>"tourism", "name"=>"Theme Parks", "zoom"=>12),
      "zoo"=>array("type"=>"tourism", "name"=>"Zoos", "zoom"=>12),
      "attraction"=>array("type"=>"tourism", "name"=>"Sightseeing &amp; Attractions", "zoom"=>14)
    )
  ),
  "shopping"=>array(
    "general"=>array(
      "mall"=>array("type"=>"shop"),
      "shopping_center"=>array("type"=>"shop"),
      "shopping_centre"=>array("type"=>"shop"),
      "convenience"=>array("type"=>"shop"),
      "department_store"=>array("type"=>"shop"),
      "general"=>array("type"=>"shop"),
      "marketplace"=>array("type"=>"amenity")
      ),
    "food"=>array(
      "supermarket"=>array("type"=>"shop"),
      "groceries"=>array("type"=>"shop"),
      "grocery"=>array("type"=>"shop"),
      "alcohol"=>array("type"=>"shop"),
      "bakery"=>array("type"=>"shop"),
      "beverages"=>array("type"=>"shop"),
      "butcher"=>array("type"=>"shop"),
      "organic"=>array("type"=>"shop"),
      "wine"=>array("type"=>"shop"),
      "fish"=>array("type"=>"shop"),
      "market"=>array("type"=>"shop")
      ),
    "sport"=>array(
      "sports"=>array("type"=>"shop"),
      "bicycle"=>array("type"=>"shop"),
      "outdoor"=>array("type"=>"shop")
    ),
    "culture"=>array(
      "books"=>array("type"=>"shop"),
      "kiosk"=>array("type"=>"shop"),
      "video"=>array("type"=>"shop"),
      "newsagent"=>array("type"=>"shop"),
      "ticket"=>array("type"=>"shop"),
      "music"=>array("type"=>"shop"),
      "photo"=>array("type"=>"shop"),
      "travel_agency"=>array("type"=>"shop")
      ),
    "car"=>array(
      "car"=>array("type"=>"shop"),
      "car_dealer"=>array("type"=>"shop"),
      "car_repair"=>array("type"=>"shop"),
      "car_parts"=>array("type"=>"shop"),
      "motorcycle"=>array("type"=>"shop")
      ),
    "fashion"=>array(
      "clothes"=>array("type"=>"shop"),
      "florist"=>array("type"=>"shop"),
      "hairdresser"=>array("type"=>"shop"),
      "shoes"=>array("type"=>"shop"),
      "fashion"=>array("type"=>"shop"),
      "jewelry"=>array("type"=>"shop"),
      "apparel"=>array("type"=>"shop"),
      "second_hand"=>array("type"=>"shop")
      ),
    "home_office"=>array(
      "computer"=>array("type"=>"shop"),
      "doityourself"=>array("type"=>"shop"),
      "electronics"=>array("type"=>"shop"),
      "hardware"=>array("type"=>"shop"),
      "hifi"=>array("type"=>"shop"),
      "dry_cleaning"=>array("type"=>"shop"),
      "furniture"=>array("type"=>"shop"),
      "garden_centre"=>array("type"=>"shop"),
      "laundry"=>array("type"=>"shop"),
      "stationery"=>array("type"=>"shop"),
      "toys"=>array("type"=>"shop"),
      "estate_agent"=>array("type"=>"shop"),
      "pet"=>array("type"=>"shop")
      ),
    "health"=>array(
      "optician"=>array("type"=>"shop"),
      "chemist"=>array("type"=>"shop"),
      "drugstore"=>array("type"=>"shop"),
      "pharmacy"=>array("type"=>"amenity")
      ),
    "othershops"=>array(
      "fixme"=>array("type"=>"shop"),
      "shop"=>array("type"=>"shop"),
      "other"=>array("type"=>"shop"),
      "vending_machine"=>array("type"=>"amenity")
    )
  ),
  "education_culture"=>array(
    "culture"=>array(
      "arts_centre"=>array("type"=>"amenity"),
      "theatre"=>array("type"=>"amenity"),
      "museum"=>array("type"=>"tourism"),
      "artwork"=>array("type"=>"tourism"),
      "fountain"=>array("type"=>"amenity"),
      "cinema"=>array("type"=>"amenity"),
      "studio"=>array("type"=>"amenity")
    ),
    "education"=>array(
      "university"=>array("type"=>"amenity"),
      "college"=>array("type"=>"amenity"),
      "school"=>array("type"=>"amenity"),
      "preschool"=>array("type"=>"amenity"),
      "kindergarten"=>array("type"=>"amenity"),
      "library"=>array("type"=>"amenity"),
      "books"=>array("type"=>"shop")
    ),
    "historic"=>array(
      "unesco_world_heritage"=>array("type"=>"historic"),
      "UNESCO_world_heritage"=>array("type"=>"historic"),
      "monument"=>array("type"=>"historic"),
      "castle"=>array("type"=>"historic"),
      "ruins"=>array("type"=>"historic"),
      "memorial"=>array("type"=>"historic"),
      "icon"=>array("type"=>"historic"),
      "archaeological_site"=>array("type"=>"historic"),
      "battlefield"=>array("type"=>"historic"),
      "wreck"=>array("type"=>"historic"),
      "wayside_cross"=>array("type"=>"historic"),
      "wayside_shrine"=>array("type"=>"historic")
    ),
    "religion"=>array(
      "place_of_worship"=>array("type"=>"amenity","subtype"=>"religion"),
      "grave_yard"=>array("type"=>"amenity", "subtype"=>"religion"),
      "cemetery"=>array("type"=>"landuse", "subtype"=>"religion"),
      "crematorium"=>array("type"=>"amenity", "subtype"=>"religion"),
      "grave"=>array("type"=>"historic"),
      "grave"=>array("type"=>"cemetery"),
    )
  ),
  "services"=>array(
    "health"=>array(
      "hospital"=>array("type"=>"amenity"),
      "doctor"=>array("type"=>"amenity"),
      "doctors"=>array("type"=>"amenity"),
      "dentist"=>array("type"=>"amenity"),
      "pharmacy"=>array("type"=>"amenity"),
      "veterinary"=>array("type"=>"amenity"),
      "red_cross"=>array("type"=>"amenity"),
      "baby_hatch"=>array("type"=>"amenity")
    ),
    "public"=>array(
      "government"=>array("type"=>"amenity"),
      "gouvernment"=>array("type"=>"amenity"),
      "townhall"=>array("type"=>"amenity"),
      "public_building"=>array("type"=>"amenity"),
      "fire_station"=>array("type"=>"amenity"),
      "police"=>array("type"=>"amenity"),
      "embassy"=>array("type"=>"amenity"),
      "courthouse"=>array("type"=>"amenity"),
      "prison"=>array("type"=>"amenity")
    ),
    "communication"=>array(
      "telephone"=>array("type"=>"amenity"),
      "emergency_phone"=>array("type"=>"amenity"),
      "post_office"=>array("type"=>"amenity"),
      "post_box"=>array("type"=>"amenity"),
      "wlan"=>array("type"=>"amenity"),
      "WLAN"=>array("type"=>"amenity")
    ),
    "disposal"=>array(
      "recycling"=>array("type"=>"amenity"),
      "toilets"=>array("type"=>"amenity"),
      "waste_disposal"=>array("type"=>"amenity")
    )
  ),
  "transport"=>array(
    "car_motorcycle"=>array(
      "fuel"=>array("type"=>"amenity"),
      "car_rental"=>array("type"=>"amenity"),
      "car_sharing"=>array("type"=>"amenity"),
      "parking"=>array("type"=>"amenity"),
      "car"=>array("type"=>"shop"),
      "car_repair"=>array("type"=>"shop")
    ),
    "pt_amenities"=>array(
      "taxi"=>array("type"=>"amenity"),
      "ticket_counter"=>array("type"=>"amenity"),
    ),
    "pipes"=>array(
      "line"=>array("type"=>"power"),
      "pipeline"=>array("type"=>"man_made")
    )
  ),
  "industry"=>array(
    "power"=>array(
      "generator"=>array("type"=>"power", "subtype"=>"power_source"),
      "station"=>array("type"=>"power"),
      "sub_station"=>array("type"=>"power"),
    ),
    "works"=>array(
      "works"=>array("type"=>"man_made"),
      "industrial"=>array("type"=>"landuse"),
    ),
  ),
  "places"=>array(
    "nature_recreation"=>array(
      "park"=>array("type"=>"leisure"),
      "nature_reserve"=>array("type"=>"leisure"),
      "common"=>array("type"=>"leisure"),
      "garden"=>array("type"=>"leisure")
    ),
    "natural"=>array(
      "peak"=>array("type"=>"natural"),
      "spring"=>array("type"=>"natural"),
      "glacier"=>array("type"=>"natural"),
      "volcano"=>array("type"=>"natural"),
      "cliff"=>array("type"=>"natural"),
      "scree"=>array("type"=>"natural"),
      "fell"=>array("type"=>"natural"),
      "heath"=>array("type"=>"natural"),
      "wood"=>array("type"=>"natural"),
      "forest"=>array("type"=>"landuse"),
      "marsh"=>array("type"=>"natural"),
      "wetland"=>array("type"=>"natural"),
      "water"=>array("type"=>"natural"),
      "beach"=>array("type"=>"natural"),
      "bay"=>array("type"=>"natural"),
      "land"=>array("type"=>"natural"),
      "cave_entrance"=>array("type"=>"natural"),
      "tree"=>array("type"=>"natural")
    )
  )
);

register_overlay(array("transport", "car_motorcycle"), "car");
register_overlay(array("transport", "pt_stops"), "pt");
register_overlay(array("transport", "pt_amenities"), "pt");
register_overlay(array("leisure_sport_tourism", "cycle_hiking"), "ch");
register_overlay(array("leisure_sport_tourism", "food_drink"), "food");
register_overlay(array("leisure_sport_tourism", "tourism"), "services");
register_overlay(array("leisure_sport_tourism", "attractions"), "culture");
register_overlay(array("education_culture", "culture"), "culture");
register_overlay(array("education_culture", "historic"), "culture");
register_overlay(array("education_culture", "religion"), "culture");
register_overlay(array("education_culture", "education"), "services");
register_overlay(array("services", "health"), "services");
register_overlay(array("services", "public"), "services");
register_overlay(array("services", "communication"), "services");
register_overlay(array("services", "disposal"), "services");
register_overlay(array("shopping"), "shop");

class infolist {
  function show_info($bounds) {
    return "template";
  }

}

$list_types=array();
function register_list($path_top, $path_sub, $class) {
  global $list_types;
  global $infolist_directory;

  $list_types[$type]=$class;
  $infolist_directory[$path_top][$path_sub]=$class;
}

function load_list($type) {
  global $list_types;

  if(!$list_types[$type])
    return null;

  return new $list_types[$type]();
}

//function call_all_infolists($bounds) {
//  global $list_types;
//  $ret="";
//
//  foreach(array_keys($list_types) as $type) {
//    if($list=load_list($type)) {
//      if(in_array("$type", $bounds[items])) {
//	$ret.="<div id='info_$type' class='item_show'>\n";
//	$ret.="<div class='item_show_title'><a href='javascript: hide_info(\"$type\")'>&uarr;</a> {$list->title()}</div>\n";
//	$ret.=$list->show_info($bounds);
//	$ret.="</div>\n";
//      }
//      else {
//	$ret.="<div id='info_$type' class='item_noshow'>\n";
//	$ret.="<div class='item_noshow_title'><a href='javascript: show_info(\"$type\")'>&darr;</a> {$list->title()}</div>\n";
//	$ret.="</div>\n";
//      }
//    }
//  }
//
//  return $ret;
//}

class infolist_item {
  private $elem;
  private $def;

  function __construct($elem, $def) {
    $this->elem=$elem;
    $this->def=$def;
  }

  //deprecated
  function format() {
    if(!$this->elem[name])
      $this->elem[name]="(unknown)";

    if($this->elem[$this->def[subtype]])
      $ret.="<li><a href='#node_{$this->elem[osm_id]}' onMouseOver='set_highlight([\"node_{$this->elem[osm_id]}\"])' onMouseOut='unset_highlight()'>{$this->elem[name]} ({$this->elem[$this->def[subtype]]})</a></li>\n";
    else
      $ret.="<li><a href='#node_{$this->elem[osm_id]}' onMouseOver='set_highlight([\"node_{$this->elem[osm_id]}\"])' onMouseOut='unset_highlight()'>{$this->elem[name]}</a></li>\n";

    return $ret;
  }
}

function call_all_infolists($bounds) {
  global $SRID;
  global $infolist_directory;
  $place_list=array();
  $list=array();
  $list_count=array();
  global $load_xml;
//    if($bounds[zoom]<15)
//      return "Zoom in for list";

    //$place_list[]="amenity='vending_machine'"; // and vending=any(array['drinks', 'food', 'sweets']))";
  $sub_types=array();
  $amenity_types=array();
  foreach($infolist_directory as $top_type=>$d1) {
    if(in_array($top_type, $bounds[items])) {
      foreach($d1 as $next_type=>$d2) {
	if(in_array("$top_type|$next_type", $bounds[items])) {
	  if(!is_string($d2)) {
	    foreach($d2 as $type=>$vals) {
//	      if(($vals[zoom]?$vals[zoom]:"15")<=$bounds[zoom]) {
		if($vals[subtype])
		  $sub_types[$vals[subtype]]=1;
		$amenity_where[$vals[type]][]=$type;
//	      }
//	      else
//		$infolist_directory[$top_type][$next_type][hidden_amenities]=1;
	    }
	  }
	}
      }
    }
  }

  if(sizeof($sub_types))
    $sub_types_sql=", \"".implode("\", \"", array_keys($sub_types))."\"";
  else
    $sub_types_sql="";

  if($amenity_where) foreach($amenity_where as $amenity_type=>$amenity_where_list) {
    $sql=array();
    foreach($amenity_where_list as $val) {
      $sql[]="\"$amenity_type\"='$val'";
    }
    $amenity_where_sql=implode(" or ", $sql);

    $qryc="(select \"$amenity_type\" as type, count(*) as count from planet_osm_point where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and ($amenity_where_sql) group by \"$amenity_type\" union ".
         "select \"$amenity_type\" as type, count(*) as count from planet_osm_polygon where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and ($amenity_where_sql) group by \"$amenity_type\" union ".
         "select \"$amenity_type\" as type, count(*) as count from planet_osm_line where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and ($amenity_where_sql) group by \"$amenity_type\");";
    $resc=sql_query($qryc);

    while($elemc=pg_fetch_assoc($resc)) {
      $list_count[$amenity_type][$elemc["type"]]+=$elemc["count"];
    }

    $sql=array();
    foreach($amenity_where_list as $val) {
      if($list_count[$amenity_type][$val]<=30)
	$sql[]="\"$amenity_type\"='$val'";
    }
    $amenity_where_sql=implode(" or ", $sql);

    if($amenity_where_sql) {
      $qry="(select 'node' as element, osm_id as id, name, \"$amenity_type\" from planet_osm_point where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and ($amenity_where_sql) union ".
	   "select 'way' as element, (CASE WHEN osm_id<0 THEN (select member_id from relation_members where relation_id=-osm_id and member_role='outer' limit 1) ELSE osm_id END) as id, name, \"$amenity_type\" from planet_osm_polygon where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and ($amenity_where_sql) union ".
      "select 'way' as element, osm_id as id, name, \"$amenity_type\" from planet_osm_line where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and ($amenity_where_sql)) order by name;";

      $load_list=array();
      $res=sql_query($qry);
      while($elem=pg_fetch_assoc($res)) {
	$load_list[]=$elem;
	$list[$amenity_type][$elem[$amenity_type]][]=$elem;
      }
    }
    else {
      $list[$amenity_type]=array();
    }
  }
  load_objects($load_list);

  $ret.="<form id='list_form' action='javascript:list_reload()'>\n";
  $ret.="<div class='list_info'>".lang("list_info")."</div>";

  foreach($infolist_directory as $top_type=>$d1) {
    if(!in_array($top_type, $bounds[items])) {
//      $ret.="<h1 id='info_$top_type'><a class='hatch' href='javascript: show_info(\"$top_type\")'>&rarr; ".lang("list_$top_type")."</a></h1>\n";
      $ret.=box_closed($top_type);
    }
    else {
      $ret1="";
//      $ret.="<h1 id='info_$top_type'><a class='hatch' href='javascript: hide_info(\"$top_type\")'>&darr; ".lang("list_$top_type")."</a></h1>\n";
      foreach($d1 as $next_type=>$d2) {
	if(!in_array("$top_type|$next_type", $bounds[items])) {
	  $ret1.=subbox_closed($top_type, $next_type);
	  //$ret.="<h2 id='info_$top_type'><a class='hatch' href='javascript: show_info(\"$top_type|$next_type\")'>&rarr; ".lang("list_$next_type")."</a></h2>\n";
	}
	else {
	  show_list_overlay($top_type, $next_type);

//	  $ret.="<h2 id='info_$top_type'><a class='hatch' href='javascript: hide_info(\"$top_type|$next_type\")'>&darr; ".lang("list_$next_type")."</a></h2>\n";
          $ret2="";
	  $count=0;
	  $last_heading="";
	  if(is_string($d2)) {
	    $x="$d2";
	    $x=new $x();
	    $ret2.=$x->show_info($bounds);
	  }
	  else {
	    if($d2[hidden_amenities])
	      $ret2.="Zoom in for more amenities.<br />\n";
	    foreach($d2 as $type=>$vals) {
	      $ret3="";

	      if(!$list_count[$vals[type]][$type]) {
		//$ret2.=subsubbox($top_type, $next_type, "{$vals[type]}_{$type}", "No amenities found.", $list_count[$vals[type]][$type]);
	      }
	      else if($list_count[$vals[type]][$type]>30) {
		$ret2.=subsubbox($top_type, $next_type, "{$vals[type]}_{$type}", "Too many amenities found. Zoom in.<br />", $list_count[$vals[type]][$type]);
	      }
	      else {
		if($list[$vals[type]][$type]) {

		  foreach($list[$vals[type]][$type] as $elem) {
  //		  if(!($x=$vals[php_type]))
  //		    $x="infolist_item";
  //		  $x=new $x($elem, $vals);
		    $x=load_object($elem);
		    $load_xml[]=$x->id;
		    $ret3.=list_entry($x->id, $x->long_name(), $x->list_description("$top_type|$next_type"));
  //		    $ret.=$x->format();
		    $count++;
		  }
		}

		$ret2.=subsubbox($top_type, $next_type, "{$vals[type]}_{$type}", make_list($ret3), $list_count[$vals[type]][$type]);
	      }
	    }
	    if((!$d2[hidden_amenities])&&(!$count))
	      $ret2.="No amenities found.<br />\n";
	  }

	  $ret1.=subbox($top_type, $next_type, $ret2);
	}
      }

      $ret.=box($top_type, $ret1);
    }
  }

  $ret.="</form>\n";

  return $ret;
}


