<?
require_once("object.php");
require_once("list.php");

$stop_types=array(
  array("amenity", "bus_station"),
  array("amenity", "ferry_terminal"),
  array("highway", "bus_stop"),
  array("railway", "tram_stop"),
  array("railway", "station"),
  array("railway", "halt"),
  array("aeroway", "terminal"),
  array("aerialway", "station")
);

register_object_type("type", "station", "object_stop");
foreach($stop_types as $t) {
  register_object_type($t[0], $t[1], "object_stop");
}

register_list("transport", "pt_stops", "infolist_stop");
register_hook("info", stop_info);

class object_stop extends object {
  function __construct($data) {
    parent::__construct($data);
  }

  function find_station() {
    $res=sql_query("select 'rel' as element, planet_osm_rels.id, type as rel_type, members from planet_osm_rels join relation_members on planet_osm_rels.id=relation_members.relation_id where relation_members.member_id='$this->only_id' and relation_members.member_type='1';");

    while($elem=pg_fetch_assoc($res)) {
      if($elem[rel_type]=="station") {
	$rel=load_object($elem);
//	$role=$rel->place()->find_member_role($this);
//	if($role!="nearby")
	  return $rel;
      }
    }

    $res=sql_query("select 'coll' as element, planet_osm_colls.id, type as coll_type from planet_osm_colls join coll_members on planet_osm_colls.id=coll_members.coll_id and planet_osm_colls.type='station' where member_id='$this->only_id' and member_type='$this->place_type_id';");
    while($elem=pg_fetch_assoc($res)) {
      if($elem[coll_type]=="station") {
	$coll=load_object($elem);
	return $coll;
      }
    }

    // nothing found ... so it's myself
    return $this;
  }
}

function stop_info($ret, $object) {
  global $stop_types;

  $is_stop=0;
  if($object->tags->get("type")=="station")
    $is_stop=1;
  foreach($stop_types as $t) {
    if($object->tags->get($t[0])==$t[1])
      $is_stop=1;
  }

  if(!$is_stop)
    return;
  
  show_overlay("pt");

  $stop_list=array();
  switch($object->place()->get_type()) {
    case "node":
      $qry="select distinct 'rel' as element, routes.id, routes.members, planet_osm_nodes.id as stop_id from planet_osm_nodes join relation_members on planet_osm_nodes.id=relation_members.member_id and relation_members.member_type='1' join planet_osm_rels routes on relation_members.relation_id=routes.id where planet_osm_nodes.id='$object->only_id' and routes.type='route';";
      break;
  case "way":
      $qry="select distinct 'rel' as element, routes.id, routes.members, planet_osm_ways.id as stop_id from planet_osm_ways join relation_members on planet_osm_ways.id=relation_members.member_id and relation_members.member_type='2' join planet_osm_rels routes on relation_members.relation_id=routes.id where planet_osm_ways.id='$object->only_id' and routes.type='route';";
      break;
    case "rel":
      $is_rel=1;
      $qry="select distinct 'rel' as element, routes.id, routes.members, planet_osm_nodes.id as stop_id from planet_osm_rels station join relation_members station_members on station.id=station_members.relation_id join planet_osm_nodes on station_members.member_id=planet_osm_nodes.id and station_members.member_type='1' join relation_members on planet_osm_nodes.id=relation_members.member_id and relation_members.member_type='1' join planet_osm_rels routes on relation_members.relation_id=routes.id where station.id='$object->only_id' and routes.type='route';";
      break;
    case "coll":
      $qry="select distinct 'rel' as element, routes.id, routes.members, planet_osm_nodes.id as stop_id from planet_osm_colls station join coll_members st_mem on station.id=st_mem.coll_id join planet_osm_nodes on planet_osm_nodes.id=st_mem.member_id and st_mem.member_type='1' join relation_members on planet_osm_nodes.id=relation_members.member_id and relation_members.member_type='1' join planet_osm_rels routes on relation_members.relation_id=routes.id where station.id='$object->only_id' and routes.type='route';";
      break;
  }

  $type=array();
  foreach($stop_types as $t)
    if($object->tags->get($t[0])==$t[1])
      $type["{$t[0]}_{$t[1]}"]=1;

  foreach($object->place->members() as $m) {
//    $ret.="<pre>".print_r($m[0], 1)."</pre>";
    foreach($stop_types as $t)
      if($m[0]->tags->get($t[0])==$t[1])
	$type["{$t[0]}_{$t[1]}"]=1;
  }

  foreach($type as $t=>$dummy) {
    $ret[]=array("general_info", lang("station_type_$t")."<br />\n");
  }

  $res=sql_query($qry);
  $list=array();
  while($elem=pg_fetch_assoc($res)) {
    $ob=load_object($elem);
    $stop=load_object("node_$elem[stop_id]");
    $list[$stop->long_name()][$ob->long_name()]=$ob;
    $list_stop[$ob->long_name()][]=$stop->id;
    $list_stop[$ob->long_name()][]=$ob->id;
  }

  $text="";

  $text.=this_format_routes($list[$object->data[tags]->get("name")], $list_stop);
  unset($list[$object->data[tags]->get("name")]);

  foreach($list as $stop_name=>$l) {
    $text.="<h4>$stop_name</h4>\n";
    $text.=this_format_routes($l, $list_stop);
    unset($list[$stop_name]);
  }

  if($text)
    $ret[]=array("routes", $text);

  return $ret;
}

function this_format_routes($list, $stops) {
  if(!$list)
    return "";

  $list_ids=array_keys($list);
  natsort($list_ids);

  foreach($list_ids as $l_id) {
    $l=$list[$l_id];
    $ret.="<li><a href='#$l->id' onMouseOver='set_highlight([\"".implode("\",\"", array_unique($stops[$l_id]))."\"])' onMouseOut='unset_highlight()'>".$l->long_name()."</a></li>\n";
  }

  return $ret;
}

class infolist_stop extends infolist {
  function show_info($bounds) {
    global $SRID;

    $importance=array();
    if($bounds[zoom]>14) {
      $importance[]="'local'";
    }
    if($bounds[zoom]>12) {
      $importance[]="'suburban'";
      $importance[]="'urban'";
    }
    if($bounds[zoom]>10) {
      $importance[]="'regional'";
    }
    if($bounds[zoom]>8) {
      $importance[]="'national'";
    }
    $importance[]="'international'";

    $qry="select stations, rel_id, coll_id from planet_osm_stations where importance in (".implode(",", $importance).") and Intersects(way, PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID))";
    $res=sql_query($qry);

    $list=array();
    while($elem=pg_fetch_assoc($res)) {
      if($elem[rel_id])
	$list[]="rel_$elem[rel_id]";
      else if($elem[coll_id])
	$list[]="coll_$elem[coll_id]";
      else {
        $x=parse_array($elem[stations]);
	$list[]="node_$x[0]";
      }
    }

    $xlist=array();
    foreach(load_objects($list) as $l) {
      $xlist[$l->long_name()][]=$l;
    }
    $list=$xlist;

    $list_s=array_keys($list);
    natsort($list_s);

    foreach($list_s as $k) {
      $x=$list[$k];
      foreach($list[$k] as $x) {
	$ret.="<li><a href='#{$x->id}' onMouseOver='set_highlight([\"{$x->id}\"])' onMouseOut='unset_highlight()'>{$x->long_name()}</a></li>\n";
      }
    }

    return $ret;


  }
}
