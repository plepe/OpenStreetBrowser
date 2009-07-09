<?
require_once("overlays.php");
register_list("transport", "pt_routes", "infolist_routes");
register_overlay(array("transport", "pt_routes"), "pt");

class infolist_routes extends infolist {
  //function 
  function title() {
    return "Public Transport Routes";
  }

  function show_info($bounds) {
    global $SRID;
    global $importance_levels;
    
    $importance=array();
    foreach($importance_levels as $net=>$zoom) {
      if($bounds[zoom]>$zoom)
	$importance[]="'$net'";
    }

    if(!sizeof($importance))
      return "";

    $qry="(select 'rel' as element, rels.id, rels.* 
           from planet_osm_rels rels ".
             "join planet_osm_line_route line_route ".
	       "on line_route.id=rels.id and line_route.way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) where rels.importance in (".implode(", ", $importance).") ".
	         "and rels.type='route' and rels.route in ('train', 'rail', 'railway', 'subway', 'light_rail', 'tram', 'trolley', 'bus', 'minibus', 'ferry') ".
           "union ".
           "select 'rel' as element, rels.id, rels.* ".
             "from planet_osm_rels rels ".
	       "join relation_members members ".
	         "on rels.id=members.relation_id ".
               "join planet_osm_point point ".
	         "on point.osm_id=members.member_id and members.member_type='1' ".
		   "and point.way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) ".
	     "where rels.importance in (".implode(", ", $importance).") ".
	       "and rels.type='route' ".
	       "and rels.route in ('train', 'rail', 'railway', 'subway', 'light_rail', 'tram', 'trolley', 'bus', 'minibus', 'ferry') ) ".
	     "order by ref"; 
    $res=sql_query($qry);

    $load_list=array();
    while($elem=pg_fetch_assoc($res)) {
      $list[]=$elem;
    }

    load_objects($list);
    return format_routes($list, $bounds);

  }
}

function format_routes($routes, $bounds=array("zoom"=>1)) {
  global $importance_names;
  global $importance_levels;
  global $route_levels;
  global $load_xml;

  $importance=array();
  foreach($importance_levels as $net=>$zoom) {
    if($bounds[zoom]>$zoom)
      $importance[]="'$net'";
  }

  $list=array();
  if($routes)
  foreach($routes as $elem) {
    if(!$list[$elem[importance]]) {
      foreach($route_levels as $level) {
	$list[$elem[importance]][$level]=array();
      }
    }
    $ob=load_object("rel_$elem[id]");
    $list[$elem[importance]][$elem[route]][$elem[id]]=$ob->long_name();
  }

  foreach($list as $importance=>$parts1) {
    foreach($parts1 as $type=>$parts) {
      natsort($list[$importance][$type]);
    }
  }

  foreach($importance_levels as $importance=>$zoom) {
    $ret.="<h4>".lang("route_$importance")."</h4>\n";
    if(!sizeof($list[$importance])) {
      if($bounds[zoom]<=$importance_levels[$importance]) {
	$ret.=lang("route_zoom");
	return $ret;
      }
      else
	$ret.=lang("route_no");
    }
    else {
      foreach($list[$importance] as $type=>$parts) {
	if(sizeof($parts)) foreach($parts as $id=>$route_list) {
	  $ob=load_object("rel_$id");
	  $text=$ob->long_name();
	  $ret.=list_entry("rel_$id", "$text");
	  $load_xml[]="rel_$id";
	}
      }
    }
  }
  return $ret;
}



