<?
require_once("relation.php");

register_relation_type("station", "relation_station");

class relation_station extends relation {
  var $id;
  var $data;
  var $member_list;

  function __construct($id) {
    if(!ereg("^[0-9]+$", $id))
      return;
    $this->id=$id;
  }

  function info() {
    if(!$this->read_data())
      return;

    $tags=$this->data[tags];

    $ret="<h1>".$tags[name]."</h1>\n";

    $ret.="<a href='javascript:zoom_to_feature()'>zoom</a>\n";

    //$res_routes=pg_query("select station.rel_id as station_id, station.role as station_role, stops.id as stop_id, stops_name.name as stopname, routes.rel_id as route_id, routes.role as stop_role, routes_desc.route, routes_desc.ref, routes_desc.name, routes_desc.network from planet_osm_rels_members station join planet_osm_nodes stops on station.mem_id=stops.id left join planet_osm_point stops_name on stops.id=stops_name.osm_id join planet_osm_rels_members routes on routes.mem_id=stops.id join planet_osm_rels routes_desc on routes.rel_id=routes_desc.id and routes_desc.type='route' where station.rel_id='$this->id' and (station.role='' or station.role='nearby') and (routes.role like 'stop_%' or routes.role like 'forward_stop_%' or routes.role like 'backward_stop_%');");
    $res_routes=pg_query("select station.id as station_id, station.tags as station_tags, station.members as station_members, stops.id as stop_id, stops_name.name as stopname, routes.id as route_id, routes.tags as stop_tags, routes.route, routes.ref, routes.name, routes.network from planet_osm_rels station join planet_osm_nodes stops on stops.id=any(station.node_parts) left join planet_osm_point stops_name on stops.id=stops_name.osm_id join planet_osm_rels routes on stops.id=any(routes.node_parts) and routes.type='route' where station.id='$this->id'");

    $node_list=array();
    while($elem_routes=pg_fetch_assoc($res_routes)) {
      $elem_routes[station_members]=parse_tags($elem_routes[station_members]);
      $elem_routes[station_role]=$elem_routes[station_members]["n$elem_routes[stop_id]"];
      $node_list[$elem_routes[stop_id]]=array($elem_routes[stopname], $elem_routes[station_role]);
      //$routes_list[$elem_routes[stop_id]][]=$elem_routes[route_id];

//      if(substr($elem_routes[stop_role], 0, 5)=="stop_") {
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][0]=$elem_routes[stop_id];
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][1]=$elem_routes[stop_id];
//      }
//      elseif(substr($elem_routes[stop_role], 0, 13)=="forward_stop_") {
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][0]=$elem_routes[stop_id];
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][1]=$elem_routes[stop_id];
//      }
//      elseif(substr($elem_routes[stop_role], 0, 14)=="backward_stop_") {
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][0]=$elem_routes[stop_id];
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][1]=$elem_routes[stop_id];
//      }
      $route=load_relation($elem_routes[route_id]);
      $route_name=$route->long_name();

      if($routes_list[$elem_routes[stopname]][$elem_routes[network]][$route_name])
	$routes_list[$elem_routes[stopname]][$elem_routes[network]][$route_name]["stop"][]=$elem_routes[stop_id];
      else
	$routes_list[$elem_routes[stopname]][$elem_routes[network]][$route_name]=array("rel"=>$route, "stop"=>array($elem_routes[stop_id]));
//      $stop_list[$elem_routes[station_role]][$elem_routes[network]][$elem_routes[stopname]]=1;
      $stop_role_list[$elem_routes[stopname]]=$elem_routes[station_role];
    }

    $res_routes=pg_query("select station.id as station_id, station.tags as station_tags, station.members as station_members, stops.id as stop_id, stops.name as stopname, routes.id as route_id, routes.tags as stop_tags, routes.route, routes.ref, routes.name, routes.network from planet_osm_rels station join planet_osm_rels stops on stops.id=any(station.rels_parts) join planet_osm_point sub_stop on sub_stop.osm_id=any(stops.node_parts) join planet_osm_rels routes on sub_stop.osm_id=any(routes.node_parts) and routes.type='route' where station.id='$this->id'");

    while($elem_routes=pg_fetch_assoc($res_routes)) {
      //print_r($elem_routes);
      $elem_routes[station_members]=parse_tags($elem_routes[station_members]);
      $elem_routes[station_role]=$elem_routes[station_members]["r$elem_routes[stop_id]"];
      $node_list[$elem_routes[stop_id]]=array($elem_routes[stopname], $elem_routes[station_role]);
      //$routes_list[$elem_routes[stop_id]][]=$elem_routes[route_id];

//      if(substr($elem_routes[stop_role], 0, 5)=="stop_") {
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][0]=$elem_routes[stop_id];
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][1]=$elem_routes[stop_id];
//      }
//      elseif(substr($elem_routes[stop_role], 0, 13)=="forward_stop_") {
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][0]=$elem_routes[stop_id];
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][1]=$elem_routes[stop_id];
//      }
//      elseif(substr($elem_routes[stop_role], 0, 14)=="backward_stop_") {
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][0]=$elem_routes[stop_id];
//	$routes_list[$elem_routes[stopname]][$elem_routes[route_id]][1]=$elem_routes[stop_id];
//      }
      $route=load_relation($elem_routes[route_id]);
      $route_name=$route->long_name();

      if($routes_list[$elem_routes[stopname]][$elem_routes[network]][$route_name])
	$routes_list[$elem_routes[stopname]][$elem_routes[network]][$route_name]["stop"][]=$elem_routes[stop_id];
      else
	$routes_list[$elem_routes[stopname]][$elem_routes[network]][$route_name]=array("rel"=>$route, "stop"=>array($elem_routes[stop_id]));
//      $stop_list[$elem_routes[station_role]][$elem_routes[network]][$elem_routes[stopname]]=1;
      $stop_role_list[$elem_routes[stopname]]=$elem_routes[station_role];
    }

    $ret.="<h2>Routes</h2>\n";
    if($tags[name])
      $ret.=show_routes($tags[name], $routes_list[$tags[name]]);
    foreach($routes_list as $stopname=>$routes) {
      if($stop_role_list[$stopname]!="nearby") {
	if($stopname!=$tags[name])
	  $ret.=show_routes($stopname, $routes);
      }
    }

    $heading=0;
    foreach($routes_list as $stopname=>$routes) {
      if($stop_role_list[$stopname]=="nearby") {
	if($stopname!=$tags[name]) {
	  if(!$heading) {
	    $heading=1;
	    $ret.="<h2>Routes at nearby stations</h2>\n";
	  }
	  $ret.=show_routes($stopname, $routes);
	}
      }
    }


    return $ret;

    if($this->data[members]["n"]) {
      $ret.="<h2>Stops</h2>\n";
      $stop_list=array();
      foreach($this->data[members]["n"] as $id=>$role) {
	if(eregi("^stop_([0-9]*)$", $role, $m)) {
	  $stop_list[$m[1]]=array();
	  $stop_list[$m[1]][0]=load_node($id);
	  $stop_list[$m[1]][1]=load_node($id);
	}
	elseif(eregi("^forward_stop_([0-9]*)$", $role, $m)) {
	  if(!$stop_list[$m[1]])
	    $stop_list[$m[1]]=array();
	  $stop_list[$m[1]][0]=load_node($id);
	}
	elseif(eregi("^backward_stop_([0-9]*)$", $role, $m)) {
	  if(!$stop_list[$m[1]])
	    $stop_list[$m[1]]=array();
	  $stop_list[$m[1]][1]=load_node($id);
	}
      }

      $stop_list_sort=array_keys($stop_list);
      natsort($stop_list_sort);

      $ret.="<table>\n";
      foreach($stop_list_sort as $num) {
	$ret.="  <tr>\n";
	$stops=$stop_list[$num];
	if((!$stops[0])&&(!$stops[1])) {
	}
	elseif(!$stops[0]) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#node_{$stops[1]->id}' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[1]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	elseif(!$stops[1]) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='details'><a href='#node_{$stops[0]->id}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[0]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	elseif($stops[0]==$stops[1]) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#node_{$stops[0]->id}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[0]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	elseif($stops[0]->tags("name")==$stops[1]->tags("name")) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#node_{$stops[0]->id},node_{$stops[1]->id}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\", \"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[1]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	else {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='details'><a href='#node_{$stops[0]->id}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[0]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#node_{$stops[1]->id}' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[1]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
      }
    }
    $ret.="</table>\n";

    return $ret;
  }
}

function find_station_rel($node_id) {
  $res=pg_query("select planet_osm_rels.id, type, members from planet_osm_rels where '$node_id'=any(node_parts);");
  while($elem=pg_fetch_assoc($res)) {
    if($elem[type]=="station") {
      $elem[members]=parse_tags($elem[members]);
      if($elem[members]["n$node_id"]!="nearby")
	return array(load_relation($elem[id], $elem[members]["n$node_id"]));
    }
  }
  return null;
}

function show_routes($stopname, $routes, $addtitle=0) {
  global $network_names;

  if(!$routes)
    return;
  if($addtitle)
    $ret.="<h3>$stopname ($addtitle)</h3>\n";
  else
    $ret.="<h3>$stopname</h3>\n";

  foreach($network_names as $network=>$name) {
    if(sizeof($routes[$network])) {
      $ret.="<h4>$name</h4>\n";
      $route_list=array_keys($routes[$network]);
      natsort($route_list);
      foreach($route_list as $route_name) {
	$route=$routes[$network][$route_name];

	$ret.="<li> <a href='#rel_{$route[rel]->id}' onMouseOver='set_highlight([\"node_".implode("\",\"node_", $route[stop])."\"])' onMouseOut='unset_hightlight()'>".$route[rel]->long_name()."</a>\n";
      }
    }
  }
  return $ret;
}


