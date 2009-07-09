<?
class way {
  var $id;
  var $long_id;
  var $data;
  var $member_list;
  var $tags;

  function __construct($id, $data=0) {
    if(!ereg("^[0-9]+$", $id))
      return;
    $this->id=$id;
    $this->long_id="way_$id";

    if($data) {
      $this->data=array();
      $this->data[id]=$data[id];
      $this->tags=$data[tags];
    }
  }

  function read_data() {
    if(!$this->id)
      return;
    if($this->data)
      return $this->data;

    $res=sql_query("select * from planet_osm_ways where id='$this->id'");
    $elem=pg_fetch_assoc($res);

    if(!$elem)
      return;

    $this->data[id]=$elem[id];   

    $this->data[nodes]=explode(",", substr($elem[nodes], 1, strlen($elem[nodes])-2));
    foreach($this->data[nodes] as $node) {
      $member_list[]=$node;
    }
    $this->data[members]=$member_list;

    $this->tags=new tags($elem[tags]);

    return $this->data;
  }

  function get_xml($xml) {
    if(!$this->read_data())
      return;

    $node=$xml->createElement("way");
    $node->setAttribute("id", $this->data[id]);

    if($this->data[members]) foreach($this->data[members] as $id) {
      $subnode=$xml->createElement("nd");
      $subnode->setAttribute("ref", $id);
      $node->appendChild($subnode);
    }

   if($this->tags) foreach($this->tags->data() as $key=>$value) {
      $subnode=$xml->createElement("tag");
      $subnode->setAttribute("k", $key);
      $subnode->setAttribute("v", $value);
      $node->appendChild($subnode);
    }

    return $node;
  }

  function info() {
    return "empty";
  }

  function member_list() {
    global $type_list_short;

    if(!$this->read_data())
      return;

    $ret=array();
    if($this->data[members]) foreach($this->data[members] as $id) {
      $ret[]="node_$id";
    }

    return $ret;
  }

  function format() {
    global $lang;
    
    $name=$this->tags->get_lang("name");

    if(!$name)
      $name=lang("noname");

    $ret.="<li><a href='#way_{$this->id}' onMouseOver='set_highlight([\"node_{$this->id}\"])' onMouseOut='unset_highlight()'>$name</a></li>\n";

    return $ret;
  }
}

$ways=array();

function load_way($id, $elem=0) {
  global $ways;

  if($ways[$id])
    return $ways[$id];

  if($elem) {
    $elem[tags]=new tags($elem[tags]);
  }

  $ob=new way($id, $elem);
  $ways[$id]=$ob;

  return $ob;
}


