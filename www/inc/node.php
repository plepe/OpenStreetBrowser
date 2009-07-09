<?
class node {
  var $id;
  var $long_id;
  var $data;
  var $member_list;

  function __construct($id) {
    if(!ereg("^[0-9]+$", $id))
      return;
    $this->id=$id;
    $this->long_id="node_$id";
  }

  function read_data() {
    if(!$this->id)
      return;
    if($this->data)
      return $this->data;

    $res=pg_query("select * from planet_osm_nodes where id='$this->id'");
    $elem=pg_fetch_assoc($res);

    if(!$elem)
      return;

    $this->data[id]=$elem[id];   

    $this->data[lat]=$elem[lat];
    $this->data[lon]=$elem[lon];

    $this->data[tags]=parse_tags($elem[tags]);

    return $this->data;
  }

  function get_xml($xml) {
    if(!$this->read_data())
      return;

    $node=$xml->createElement("node");
    $node->setAttribute("id", $this->data[id]);
    $node->setAttribute("lat", $this->data[lat]);
    $node->setAttribute("lon", $this->data[lon]);

    if($this->data[tags]) foreach($this->data[tags] as $key=>$value) {
	$subnode=$xml->createElement("tag");
	$subnode->setAttribute("k", $key);
	$subnode->setAttribute("v", $value);
	$node->appendChild($subnode);
    }

    return $node;
  }

  function info() {
    return "default node";
  }

  function tags($key) {
    if(!$this->read_data())
      return;

    return $this->data[tags][$key];
  }

  function member_list() {
    return array();
  }
}


$nodes=array();

function load_node($id) {
  global $nodes;
  global $node_types;

  if($nodes[$id])
    return $nodes[$id];

  if(eregi("^[0-9]*$", $id)) {
    $res=pg_query("select * from planet_osm_point where osm_id='$id'");
    $elem=pg_fetch_assoc($res);
    $class="node";
    foreach($node_types as $key=>$x) {
      foreach($x as $type=>$c) {
	if($elem[$key]==$type)
	  $class=$c;
      }
    }

    $elem=new $class($id);
    $nodes[$id]=$elem;

    return $elem;
  }
}

$node_types=array();
function register_node_type($key, $type, $class) {
  global $node_types;

  $node_types[$key][$type]=$class;
}

