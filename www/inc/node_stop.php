<?
require_once("node.php");

$valid_stop_types=array(
  "highway"=>array("bus_stop"),
  "railway"=>array("tram_stop", "station", "halt"),
  "amenity"=>array("bus_station", "ferry_terminal"));
//"aerialway"=>array("station")

foreach($valid_stop_types as $key=>$x)
  foreach($x as $value)
    register_node_type($key, $value, "node_stop");

class node_stop extends node {
  var $id;
  var $long_id;
  var $data;
  var $member_list;

  function info() {
    global $load_xml;
    if(!$this->read_data())
      return;

    $tags=$this->data[tags];

    $ret="<h1>".$tags[name]."</h1>\n";

    $ret.="<a href='javascript:zoom_to_feature()'>zoom</a>\n";

    // Search nearby stops with the same name
    $res=pg_query("select dst.osm_id, dst.name, dst.railway, dst.highway from planet_osm_point src, planet_osm_point dst where src.osm_id='$this->id' and src.name=dst.name and Distance(src.way, dst.way)<100 and (dst.highway='bus_stop' or dst.railway='tram_stop' or dst.railway='station' or dst.railway='halt' or dst.amenity='bus_station' or dst.aeroway='station' or dst.amenity='ferry_terminal')"); // THIS HAS TO BE OPTIMIZED - 1.2 sec
    // TODO: aeroway is wrong
    $stops=array($this->id);
    while($elem=pg_fetch_assoc($res)) {
      $stops[]=$elem[osm_id];
      $load_xml[]="node_$elem[osm_id]";
    }

    $stops=array_unique($stops);

    $routes=array();
    foreach($stops as $stop) {
      $res_routes=pg_query("select routes.id as route_id, routes.ref as route_ref, routes.name as route_name, routes.network as route_network from planet_osm_rels routes where '$stop'=any(routes.node_parts)");
      while($elem_routes=pg_fetch_assoc($res_routes)) {
	$route=load_relation($elem_routes[route_id]);

	if($route_list[$elem_routes[route_network]][$route->long_name()])
	  $route_list[$elem_routes[route_network]][$route->long_name()]["stop"][]=$stop;
	else
	  $route_list[$elem_routes[route_network]][$route->long_name()]=array("rel"=>$route, "stop"=>array($stop));
      }
    }

    $ret.="<h2>Routes</h2>\n";
    $ret.=show_routes($tags[name], $route_list);

    return $ret;
  }
}
