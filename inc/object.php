<?
// global vars
$objects=array();
$object_types=array();
$output_xml=array();
$object_elements=array("node"=>"N", "way"=>"W", "rel"=>"R", "relation"=>"R");
$object_element_ids=array("node"=>1, "way"=>2, "rel"=>3, "coll"=>4);
$object_element_shorts=array("n"=>"node", "r"=>"rel", "w"=>"way", "c"=>"coll");
$object_element_data_type=array("N"=>"node", "R"=>"rel", "W"=>"way");
$object_place_types=array("n"=>"node", "w"=>"way", "r"=>"relation");
$tag_preloaded=array();
$priority_chapter=array(
  "general_info"=>-5,
  "wikipedia"=>-1,
  "routing"=>1,
  "internal"=>5
);


function tag_preload($elem) {
  global $tag_preloaded;

  $tag_preloaded[$elem['element']][$elem['id']][$elem['k']]=$elem['v'];
}

class object {
  public $id;
  public $data;
  public $tags;
  public $place;
  public $element;
  public $only_id;
  public $place_type;
  public $place_type_id;

  function __construct($id, $data=null) {
    global $object_place_types;

    $this->id = $id;

    if(preg_match("/^([" . implode("", array_keys($object_place_types)) . "])([0-9]+)$/", $this->id, $m)) {
      $this->type = $object_place_types[$m[1]];
      $this->num_id= $m[2];
    }
    else {
      throw new Exception("object: illegal ID");
    }

    if($data === null) {
      $r = overpass_query("[out:json];{$this->type}({$this->num_id});out meta geom;");

      if((!$r) ||
         (!sizeof($r))) {
        throw new Exception("object {$this->id} does not exist");
      }

      $data = $r[0];
    }

    $tags = $data['tags'];
    $tags['osm:id'] = $data['id'];
    $tags['osm:timestamp'] = $data['timestamp'];
    $tags['osm:version'] = $data['version'];
    $tags['osm:user'] = $data['user'];
    $tags['osm:user_id'] = $data['uid'];
    $tags['osm:changeset'] = $data['changeset'];

    $this->data = $data;
    $this->tags = new tags($tags);
  }

  function long_name($lang="", $name_def=0) {
    if(!$name_def)
      $name_def="[ref] - [name];[name];[ref];[operator]";

    return $this->tags->parse($name_def, $lang);
  }

  function list_description($list) {
    $ret=array();
    call_hooks("list_description", $ret, $this, $list);

    return $ret;
  }

  function info($param) {
    global $object_elements;
    global $priority_chapter;

    if(!$this->read_data())
      return;

    $param["info_noshow"]=explode(",", $param["info_noshow"]);

    $ret="<div class='object'>\n";
    $name_lang=$this->tags->get_lang("name");
    $name     =$this->tags->get("name");

    if($name_lang!=$name)
      $ret.="<h1>$name_lang ($name)</h1>\n";
    else
      $ret.="<h1>$name_lang</h1>\n";

    $ret.="<div class='obj_actions'>\n";
    $ret.="<a class='zoom' href='#' onClick='redraw()'>".lang("info_back")."</a>\n";
    $ret.="<a class='zoom' href='javascript:zoom_to_feature()'>".lang("info_zoom")."</a>\n";
    $ret.="</div>\n";

    $data=array();

    call_hooks("info", $data, $this, $param);

    $chapter=array();

    foreach($data as $d) {
      $chapter[$d[0]].=$d[1];
    }

    foreach($chapter as $title=>$content) {
      $chapter_list[$priority_chapter[$title]][]=$title;
    }

    ksort($chapter_list);

    $ret.="<form id='info' action='javascript:info_change()'>\n";
    foreach($chapter_list as $title_list) {
      foreach($title_list as $title) {
	$content=$chapter[$title];
	if($content) {
//	  $ret.="<div class=\"object_chapter\" id=\"object_$title\">\n";
	  if(in_array($title, $param['info_noshow']))
	    $ret.=infobox_closed($title);
	  else
	    $ret.=infobox($title, $content);
//	  $ret.="<h2>".lang("head_$title")."</h2>\n";
//	  $ret.=$content;
//	  $ret.="</div>\n";
	}
      }
    }
    $ret.="</form>\n";

    $ret.="</div>\n";
    return $ret;
  }

  function read_data() {
    // load missing data
    return 1;
  }

  function place() {
    if($this->place)
      return $this->place;

    $x="place_{$this->place_type}";
    $this->place=new $x($this->data);

    return $this->place;
  }

  function get_xml($parent, $root, $inc_children=0, $bounds=0) {
    global $output_xml;
    global $object_elements;
    if(in_array($this->id, $output_xml))
      return;

    $this->read_data();

    $obj=$root->createElement($object_elements[$this->data['element']]);
    $parent->appendChild($obj);
    if($this->data['subid'])
      $obj->setAttribute("id", $this->data['id']."_".$this->data['subid']);
    else
      $obj->setAttribute("id", $this->data['id']);
    $output_xml[]=$this->id;

    $this->place()->get_xml($obj, $root, $inc_children, $bounds);
    $this->tags->get_xml($obj, $root);

  }

  function export_dom($document) {
    $match=$document->createElement("match");
    $match->setAttribute("id", $this->id);

    $tags=$this->tags->export_dom($document);
    for($i=0; $i<sizeof($tags); $i++) {
      $match->appendChild($tags[$i]);
    }

    return $match;
  }

  function export_array() {
    $ret=array();

    $ret['osm_id']=$this->id;
    $ret['osm_tags']=$this->tags->export_array();

    return $ret;
  }

  /**
   * returns list of members, e.g. array("way_123"=>"stop", ...)
   * @return array key: member_id, value: member_role
   */
  function members() {
    global $object_element_data_type;
    $ret=null;

    if(isset($this->members))
      return $this->members;

    switch($this->element) {
      case "rel":
	 $id=substr($this->id, 1);
	 $ret=array();

         $res=sql_query("select * from relation_members where relation_id='$id'");
	 while($elem=pg_fetch_assoc($res)) {
	   $elem_id="{$elem['member_type']}{$elem['member_id']}";
           $ret[$elem_id]=$elem['member_role'];
	 }
    }

    $this->members=$ret;

    return $ret;
  }

  /**
   * returns list of relations this object is member of, e.g.
   * array("rel_123"=>"stop", ...)
   * @return array key: relation_id, value: member_role
   */
  function member_of() {
    global $object_element_data_type;
    $ret=null;

    if(isset($this->member_of))
      return $this->member_of;

    $id=substr($this->id, 1);
    $data_type=substr($this->id, 0, 1);
    $ret=array();

    $res=sql_query("select * from relation_members where member_id='$id' and member_type='$data_type'");
    while($elem=pg_fetch_assoc($res)) {
      $elem_id="R{$elem['relation_id']}";
      $ret[$elem_id]=$elem['member_role'];
    }

    $this->member_of=$ret;

    return $ret;
  }
}

// id                      object identifier, e.g. 'n1234'
// data                    data from overpass query (or null)
//
// returns: an object of type 'object' or null if object does not exist
function load_object($id, $data=null) {
  global $objects;

  // obsolete function
  if(!is_string($id))
    throw new Exception("\$elem not string: ". print_r($id, 1));

  if(!array_key_exists($id, $objects)) {
    try {
      $objects[$id] = new object($id, $data);
    }
    catch(Exception $e) {
      $objects[$id] = null;
      return null;
    }
  }

  return $objects[$id];
}

function register_object_type($key, $type, $class) {
  global $object_types;

  $object_types[$key][$type]=$class;
}

// using load_objects is faster then loading each object by its own
function load_objects($list, $options=array()) {
  global $objects;
  global $object_place_types;
  $ret = array();
  $todo = array();
  $query = "";

  foreach($list as $id) {
    if(array_key_exists($id, $objects)) {
      $ret[$id] = $objects[$id];
    }
    else {
      if(preg_match("/^([" . implode("", array_keys($object_place_types)) . "])([0-9]+)$/", $id, $m)) {
        $type = $object_place_types[$m[1]];
        $num_id= $m[2];
        $query .= "{$type}({$num_id});";
        $todo[$id] = true;
      }
    }
  }

  if(strlen($query)) {
    $query = "[out:json];({$query});out meta geom;";

    $r = overpass_query($query);
    // print "$query"; print_r($r);

    if($r)
      foreach($r as $e) {
        $id = substr($e['type'], 0, 1) . $e['id'];
        $o = load_object($id, $e);

        $ret[$id] = $o;
        unset($todo[$id]);
      }
  }

  if(sizeof($todo)) {
    trigger_error("load_objects(): objects " . implode(", ", array_keys($todo)) . " could not be loaded");
  }

  return $ret;
}

function ajax_object_load_more_tags($param) {
  $ob=load_object($param['id'], null, $param);
  $tags=explode(",", $param['tags']);
  $ret=array();

  foreach($tags as $t) {
    if($v=$ob->tags->get($t)) {
      $ret[$t]=$v;
    }
  }

  return $ret;
}

function ajax_load_object($param, $xml) {
  $ob=load_object($param['ob'], null, $param);

  $result=dom_create_append($xml, "result", $xml);
  $node=$ob->export_dom($xml);

  if($node)
    $result->appendChild($node);
}
