<?
class relation {
  var $id;
  var $long_id;
  var $data;
  var $member_list;

  function __construct($id) {
    if(!ereg("^[0-9]+$", $id))
      return;
    $this->id=$id;
    $this->long_id="rel_$id";
  }

  function read_data() {
    if(!$this->id)
      return;
    if($this->data)
      return $this->data;

    $res=pg_query("select * from planet_osm_rels where id='$this->id'");
    $elem=pg_fetch_assoc($res);

    if(!$elem)
      return;

    $this->data[id]=$elem[id];   

    $this->data[members]=parse_tags($elem[members]);
    foreach($this->data[members] as $member=>$role) {
      $member_list[substr($member, 0, 1)][substr($member, 1)]=$role;
    }
    $this->data[members]=$member_list;

    $this->data[tags]=parse_tags($elem[tags]);

    $this->data[network]=$elem[network];

    return $this->data;
  }

  function get_xml($xml) {
    global $type_list;

    if(!$this->read_data())
      return;

    $node=$xml->createElement("relation");
    $node->setAttribute("id", $this->data[id]);

    foreach($this->data[members] as $type=>$list) {
      if($list) foreach($list as $subid=>$role) {
	$subnode=$xml->createElement("member");
	$subnode->setAttribute("type", $type_list[$type]);
	$subnode->setAttribute("ref", $subid);
	$subnode->setAttribute("role", $role);
	$node->appendChild($subnode);
      }
    }

    foreach($this->data[tags] as $key=>$value) {
      $subnode=$xml->createElement("tag");
      $subnode->setAttribute("k", $key);
      $subnode->setAttribute("v", $value);
      $node->appendChild($subnode);
    }

    return $node;
  }

  function info() {
    return "";
  }

  function member_list() {
    global $type_list_short;

    if(!$this->read_data())
      return;

    $ret=array();
    foreach($this->data[members] as $type=>$list) {
      if($list) foreach($list as $subid=>$role) {
	$ret[]="{$type_list_short[$type]}_{$subid}";
      }
    }

    return $ret;
  }
}

$relations=array();

function load_relation($id) {
  global $relation_types;
  global $relations;

  if($relations[$id])
    return $relations[$id];

  if(eregi("^[0-9]*$", $id)) {
    $res=pg_query("select type from planet_osm_rels where id='$id'");
    $elem=pg_fetch_assoc($res);
    if(!($class=$relation_types[$elem[type]]))
      $class="relation";

    $elem=new $class($id);
    $relations[$id]=$elem;

    return $elem;
  }
}

$relation_types=array();
function register_relation_type($type, $class) {
  global $relation_types;

  $relation_types[$type]=$class;
}

function load_element($id) {
  $obj=explode("_", $id);
  switch($obj[0]) {
    case "rel":
      $r=load_relation($obj[1]);
      break;
    case "way":
      $r=load_way($obj[1]);
      break;
    case "node":
      $r=load_node($obj[1]);
      break;
  }

  return $r;
}

