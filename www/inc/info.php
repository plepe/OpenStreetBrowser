<?
$hide_tags=array("created_by", "openGeoDB:*", "opengeodb:*");
$hide_tags_preg="/(".implode("|", $hide_tags).")/";

function amenity_info($ret, $object) {
  $ret[]=array("general_info", $object->tags->compile_text("#tag/amenity#: #tag_amenity/%amenity%#<br />\n"));
}

register_hook("info", amenity_info);

// ADDRESS
function address_info($ret, $object) {
  $ret[]=array("address", $object->tags->compile_text("%addr:street% %addr:housenumber%<br />\n"));
  $ret[]=array("address", $object->tags->compile_text("%addr:postcode% %addr:city%<br />\n"));
}

register_hook("info", address_info);

// SHOP
function shop_info($ret, $object) {
  $ret[]=array("general_info", $object->tags->compile_text("#tag/shop#: %shop%<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/vending#: %vending%<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/opening_hours#: %opening_hours%<br />\n"));

  if($object->tags->get("shop"))
    show_overlay("shop");
  if($object->tags->get("amenity")=="marketplace")
    show_overlay("shop");
}

register_hook("info", shop_info);

// FOOD & DRINK
function food_drink_list_description($ret, $object, $list) {
  if($value=$object->tags->get("cuisine"))
    $ret[]=lang("tag_cuisine/$value");
}

function food_drink_info($ret, $object) {
  global $infolist_directory;

  $ret[]=array("general_info", $object->tags->compile_text("#tag/cuisine#: #tag_cuisine/%cuisine%#<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/old_name#: %old_name%<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/food#: #yes/%food%#<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/accomodation#: %accomodation%<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/real_ale#: %real_ale%<br />\n"));

  if($infolist_directory[leisure_sport_tourism][food_drink][$object->tags->get("amenity")])
    show_overlay("food");
}

register_hook("list_description", food_drink_list_description);
register_hook("info", food_drink_info);

// SPORT
function sport_list_description($ret, $object, $list) {
  if($list!="leisure_sport_tourism|sport")
    if($value=$object->tags->get("sport"))
      $ret[]=lang("tag_sport/$value");
}

function sport_info($ret, $object) {
  $ret[]=array("general_info", $object->tags->compile_text("#tag/sport#: %sport%<br />\n"));
}

register_hook("list_description", sport_list_description);
register_hook("info", sport_info);

// Network
function network_info($ret, $object) {
  if($object->tags->get("type")!="network") {
    $ret[]=array("general_info", $object->tags->compile_text("#tag/operator#: %operator%<br />\n"));
    $ret[]=array("general_info", $object->tags->compile_text("#tag/network#: %network%<br />\n"));
    return;
  }

  $ret[]=array("general_info", "This entity describes a network<br />\n");
  $ret[]=array("general_info", $object->tags->compile_text("#tag/operator#: %operator%<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/network#: %network%<br />\n"));

  $text="";

  $res=sql_query("select 'rel' as element, c.id, c.members from planet_osm_rels r join relation_members on r.id=relation_members.relation_id join planet_osm_rels c on c.id=relation_members.member_id and relation_members.member_type='3' where r.id='$object->only_id'");
  $list=array();
  while($elem=pg_fetch_assoc($res)) {
    $x=load_object($elem);
    if($list[$x->long_name()])
      $list[]=$x;
    else
      $list[$x->long_name()]=$x;
  }

  $list_s=array_keys($list);
  natsort($list_s);

  foreach($list_s as $k) {
    $x=$list[$k];
    $text.=list_entry($x->id, $x->long_name());
  }

  $ret[]=array("members", $text);
}

register_hook("info", network_info);

// RELIGION
function religion_list_description($ret, $object, $list) {
  if($value=$object->tags->get("religion"))
    $ret[]=lang("tag_religion/$value");
}

function religion_info($ret, $object) {
  $ret[]=array("general_info", $object->tags->compile_text("#tag/religion#: #tag_religion/%religion%#<br />\n"));
  $ret[]=array("general_info", $object->tags->compile_text("#tag/denomination#: #tag_denomination/%denomination%#<br />\n"));
}

register_hook("list_description", religion_list_description);
register_hook("info", religion_info);

// INTERNAL
function osm_info($ret, $object, $param) {
  global $load_xml;
  global $object_elements;

  if(in_array("internal", $param["info_noshow"])) {
    $ret[]=array("internal", "X");
    return;
  }

  $r="";
  $r.="<h4>Info</h4>\n";
  $r.="Type/ID: ".$object->id."<br>\n";
  if($object->element!="coll") {
    $res_info=sql_query("select * from ".$object_elements[$object->element]."s where ".
      "id='$object->only_id'");
    $elem_info=pg_fetch_assoc($res_info);
    $r.="Last changed: ".$elem_info[tstamp]." by ".$elem_info[user_name]."<br/>\n";
  }

  $r.="<h4>Tags</h4>\n";
  foreach($object->tags->data() as $k=>$v) {
    $r.="$k: $v<br/>\n";
  }

// Members of
  $qry="select 'rel' as element, relation_id as id, member_role from relation_members where member_id='$object->only_id' and member_type='$object->place_type_id' ".
    "union ".
    "select 'coll' as element, coll_id as id, member_role from coll_members where member_id='$object->only_id' and member_type='$object->place_type_id'";

  $list=array();
  $res=sql_query($qry);
  while($elem=pg_fetch_assoc($res)) {
    $list[]=$elem;
  }

  if(sizeof($list)) {
    $r.="<h4>Members of</h4>\n";
    load_objects($list);

    foreach($list as $elem) {
      $ob=load_object($elem);
      $r.=list_entry($ob->id, $ob->long_name(), array($ob->tags->get("type"), ($elem[member_role]?"as $elem[member_role]":"no role")));
      $load_xml[]=$ob->id;
    }

  }

  if($object->place()->members()) {
    $r.="<h4>Members</h4>\n";
    foreach($object->place()->members() as $member) {
      $load_xml[]=$member[0]->id;
      $r.=list_entry($member[0]->id, $member[0]->long_name(), $member[1]?array($member[1]):null);
    }
  }

  $r.="<h4>".lang("head_actions")."</h4>\n";
  if(($object->place_type=="node")||($object->place_type=="way")||($object->place_type=="rel")) {
    $r.="<li><a href='http://www.openstreetmap.org/browse/{$object_elements[$object->place_type]}/$object->only_id'>".lang("action_browse")."</a></li>\n";
  }
  $geo=$object->place()->get_centre();
  if(sizeof($geo)) {
//    $r.=print_r($geo, 1);
    $geo=goole_to_utm($geo, 1);
    $r.="<li><a href='http://www.openstreetmap.org/edit?lat=$geo[lat]&lon=$geo[lon]&zoom=16'>".lang("action_edit")."</a></li>\n";
  }

  $ret[]=array("internal", $r);
}

register_hook("info", osm_info);

// WIKIPEDIA
function wikipedia($ret, $object) {
  $r=ext_wikipedia($object);

  if($r)
    $ret[]=array("wikipedia", $r);
}

register_hook("info", wikipedia);

// PLACES
function places_info($ret, $object) {
  $tags=$object->tags;

  if(!$tags->get("place"))
    return;

  $r.=lang("tag/place").": ".lang("place/".$tags->get("place"))."<br />\n";
  $r.=$tags->compile_text("#tag/capital#: #yes/%capital%#<br />\n");
  $r.=$tags->compile_text("#tag/is_in#: %is_in%<br />\n");
  $r.=$tags->compile_text("#tag/population#: %population%<br />\n");

  $ret[]=array("general_info", $r);
}
register_hook("info", places_info);

// PLACES
function comm_info($ret, $object) {
  $tags=$object->tags;

  $r.=$tags->compile_text("#tag/website#: <a href='%website%'>%website%</a><br />\n");
  $r.=$tags->compile_text("#tag/website#: <a href='%url%'>%url%</a><br />\n");

  $ret[]=array("general_info", $r);
}
register_hook("info", comm_info);

// CULTURE
function culture_info($ret, $object) {
  if(in_array($object->tags->get("amenity"), 
      array("arts_centre", "theatre", "cinema", "fountain", "studio", "place_of_worship")))
    show_overlay("culture");

  if($object->tags->get("historic"))
    show_overlay("culture");

  if(in_array($object->tags->get("tourism"), array("museum", "artwork", "attraction", "viewpoint", "theme_park", "zoo", "yes")))
    show_overlay("culture");
}

register_hook("info", culture_info);

function services_info($ret, $object) {
  global $infolist_directory;

  if(in_array($object->tags->get("tourism"), array("hotel", "hostel", "motel", "guest_house", "camp_site", "caravan_site", "mountain_hut", "chalet", "information")))
    show_overlay("services");
  if(in_array($object->tags->get("amenity"), array("post_box", "post_office", "post_office;atm", "bank", "bank;atm", "atm;bank", "atm", "recycling", "hospital", "emergency_phone", "fire_station", "police", "pharmacy", "baby_hatch", "dentist", "doctors", "veterinary", "university", "college", "school", "preschool", "kindergarten", "library", "government", "gouvernment", "public_building", "court_house", "embassy", "prison", "townhall")))
    show_overlay("services");
  if($object->tags->get("man_made"))
    show_overlay("services");
}

register_hook("info", services_info);

function car_amenities_info($ret, $object) {
  if((in_array($object->tags->get("amenity"), array("parking", "car_rental", "car_sharing", "car_repair", "fuel")))
     or (in_array($object->tags->get("highway"), array("services", "emergency_access_point", "motorway_junction"))))
    show_overlay("car");
}
register_hook("info", car_amenities_info);

function housenumbers($ret, $object) {
  global $load_xml;
  if($object->tags->get("type")!="street")
    return;

  $done=array();
  foreach($object->place()->members() as $member) {
    $member=$member[0];

    if($h=$member->tags->get("addr:housenumber")) {
      $done[$h]=$member->id;
    }
  }

  $res=sql_query("select member_id, (select \"addr:housenumber\" from way_nodes join planet_osm_point on node_id=osm_id where way_id=member_id and sequence_id=0) as first, (select \"addr:housenumber\" from way_nodes join planet_osm_point on node_id=osm_id where way_id=member_id order by sequence_id desc limit 1) as last from coll_members where coll_id='$object->only_id'");
  $list_ways=array();
  while($elem=pg_fetch_assoc($res)) {
    $list_ways[$elem[member_id]]=$elem;
  }

  foreach($object->place()->members() as $member) {
    $member=$member[0];

    if($i=$member->tags->get("addr:interpolation")) {
      $first=$list_ways[$member->only_id][first];
      $last=$list_ways[$member->only_id][last];
      if($first>$last) {
	$h=$last;
	$last=$first;
	$first=$h;
      }

      if($last>$first) {
        $hp=($i=="odd"||$i=="even")?2:1;
	for($h=$first; $h<=$last; $h+=$hp) {
	  if(!$done[$h])
	    $done[$h]="{$member->id}_$h";
	}
      }
    }
  }

  $done_ind=array_keys($done);
  natsort($done_ind);
  foreach($done_ind as $h) {
    $member_id=$done[$h];
    $member=load_object($member_id);
    if($member->tags->get("name"))
      $text.=list_entry($member->id, $h, array($member->long_name()));
    else
      $text.=list_entry($member->id, $h);
    $load_xml[]=$member->id;
  }

  if($text)
    $ret[]=array("housenumbers", $text);
}

register_hook("info", housenumbers);

// CEMETERY, GRAVES, ...
function cemetery_info($ret, $object) {
  $is_ceme=0;
  $is_grave=0;
  $text="";

  if($x=$object->tags->get("cemetery")) {
    show_overlay("culture");
    if($x=="grave")
      $is_grave=1;
  }
  if($object->tags->get("amenity")=="grave_yard") {
    show_overlay("culture");
    $is_ceme=1;
  }
  if($object->tags->get("landuse")=="cemetery") {
    show_overlay("culture");
    $is_ceme=1;
  }

  if(($object->element=="way")&&($is_ceme)) {
    $qry="(select 'node' as element, gra.osm_id as id, gra.name from planet_osm_polygon ceme join planet_osm_point gra on gra.way&&ceme.way and Within(gra.way, ceme.way) and (gra.cemetery='grave' or gra.historic='grave') where ceme.osm_id='$object->only_id' union select 'way' as element, gra.osm_id as id, gra.name from planet_osm_polygon ceme join planet_osm_polygon gra on gra.way&&ceme.way and Within(gra.way, ceme.way) and (gra.cemetery='grave' or gra.historic='grave') where ceme.osm_id='$object->only_id') order by name";
    $res=sql_query($qry);

    $list=array();
    while($elem=pg_fetch_assoc($res)) {
      $list[]=$elem;
    }

    load_objects($list);

    foreach($list as $l) {
      $l=load_object($l);
      $text.=list_entry($l->id, $l->long_name());
    }

    if($text)
      $ret[]=array("graves", $text);
  }

  if($is_grave) {
    switch($object->element) {
      case "node":
        $qry="select 'way' as element, ceme.osm_id as id, ceme.name from planet_osm_point gra join planet_osm_polygon ceme on gra.way&&ceme.way and Within(gra.way, ceme.way) and (ceme.landuse='cemetery' or ceme.amenity='grave_yard') where gra.osm_id='$object->only_id'";
	break;
      case "way":
        $qry="select 'way' as element, ceme.osm_id as id, ceme.name from planet_osm_polygon gra join planet_osm_polygon ceme on gra.way&&ceme.way and Within(gra.way, ceme.way) and (ceme.landuse='cemetery' or ceme.amenity='grave_yard') where gra.osm_id='$object->only_id'";
    }
    $res=sql_query($qry);

    while($elem=pg_fetch_assoc($res)) {
      $list[]=$elem;
    }

    load_objects($list);

    foreach($list as $l) {
      $l=load_object($l);
      $text.=lang("grave_is_on")." <a href='#$l->id'>{$l->long_name()}</a><br/>";
    }

    if($text)
      $ret[]=array("general_info", $text);
  }
}

register_hook("info", cemetery_info);

function power_info($ret, $object) {
  $text="";

  if($power=$object->tags->get("power")) {
    $text.=lang("power_".$object->tags->get("power"))."<br />\n";

    switch($power) {
      case "line":
        $text.=$object->tags->compile_text("#tag/voltage#: %voltage%<br />\n");
        $text.=$object->tags->compile_text("#tag/cables#: %cables%<br />\n");
        $text.=$object->tags->compile_text("#tag/wires#: %wires%<br />\n");
        break;
      case "generator":
        $text.=$object->tags->compile_text("#tag/power_source#: #tag_power_source/%power_source%#<br />\n");
    }

    $ret[]=array("general_info", $text);
  }
}

function power_list_description($ret, $object, $list) {
  switch($object->tags->get("power")) {
    case "generator":
      if($value=$object->tags->get("power_source"))
	$ret[]=lang("tag_power_source/$value");
  }
}

register_hook("info", power_info);
register_hook("list_description", power_list_description);

function pt_info($ret, $object) {
  switch($object->tags->get("railway")) {
    case "platform":
      show_overlay("pt");
  }
}
register_hook("info", pt_info);

function other_tags($ret, $object) {
  global $hide_tags_preg;

  foreach($object->tags->data() as $key=>$v) {
    if((!in_array($key, $object->tags->compiled_tags))&&
       (!preg_match($hide_tags_preg, $key))) {
      $ret[]=array("general_info", $object->tags->compile_text("#tag/$key#: #$key_%$key%#<br />\n"));
    }
  }
}
register_hook("info", other_tags);
