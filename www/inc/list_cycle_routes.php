<?
register_list("leisure_sport_tourism", "ch_routes", "infolist_cycle_routes");
register_overlay(array("leisure_sport_tourism", "ch_routes"), "ch");

class infolist_cycle_routes extends infolist {
  function show_info($bounds) {
    global $SRID;
    
    $importance=array();
    if($bounds[zoom]>10) {
      $importance[]="'suburban'";
      $importance[]="'urban'";
      $importance[]="'local'";
    }
    if($bounds[zoom]>7) {
      $importance[]="'region'";
    }
    $importance[]="'national'";
    $importance[]="'international'";

    $qry="select distinct 'rel' as element, planet_osm_rels.id, planet_osm_rels.* from planet_osm_line_route join planet_osm_rels on planet_osm_line_route.id=planet_osm_rels.id where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and planet_osm_line_route.importance in (".implode(", ", $importance).") and type='route' and planet_osm_rels.route=any(array['bicycle','hiking','mtb','foot']) order by planet_osm_rels.ref"; 
    $res=pg_query($qry);

    $list=array();
    while($elem=pg_fetch_assoc($res)) {
      $list[]=$elem;
    }

    load_objects($list);
    return format_routes($list, $bounds);

  }

function format_routes($routes, $bounds=array("zoom"=>1)) {
  foreach($routes as $r) {
    $ret.=list_entry($r->id, $r->long_name());
  }
  return $ret;

  global $importance_names;
  global $importance_levels;
  global $route_levels;

  $importance=array();
  if($bounds[zoom]>10) {
    $importance[]="urban";
  }
  if($bounds[zoom]>7) {
    $importance[]="region";
  }
  $importance[]="national";
  //$importance[]="international";

  $list=array();
  $elem[importance]="national";
  foreach($routes as $elem) {
//    if(!$list[$elem[importance]]) {
//      foreach($route_levels as $level) {
//	$list[$elem[importance]][$level]=array();
//      }
//    }
    $list[$elem[importance]][$elem[route]][]="$elem[ref]|$elem[name]|$elem[route]|$elem[id]";
  }

  foreach($list as $importance=>$parts1) {
    foreach($parts1 as $type=>$parts) {
      natsort($list[$importance][$type]);
    }
  }

  foreach($importance_names as $importance=>$name) {
    $ret.="<h4>$name</h4>\n";
    if(!sizeof($list[$importance])) {
      if($bounds[zoom]<=$importance_levels[$importance])
	$ret.="Zoom in for list of routes";
      else
	$ret.="No routes founds";
    }
    else {
      foreach($list[$importance] as $type=>$parts) {
	if(sizeof($parts)) foreach($parts as $route_list) {
	  $x=explode("|", $route_list);
	  if($x[0]==$x[1])
	    $text="$x[0]";
	  elseif($x[1]&&$x[0])
	    $text="$x[0] - $x[1]";
	  elseif($x[1]&&!$x[0])
	    $text="$x[1]";
	  else
	    $text="$x[0]";
	  $ret.="<li class='details_$x[2]'><a href='#rel_$x[3]'>$text</a></li>\n";
		    $load_xml[]=$x->id;
	}
      }
    }
  }
  return $ret;
}


}


