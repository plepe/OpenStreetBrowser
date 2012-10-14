<?
$plugins[]="load_object";

class place {
  function get_type() { return "default"; }
  public $data;

  function __construct($data) {
    $this->data=$data;
  }

  function get_xml($obj, $root, $bounds=0) {
    $obj->setAttribute("way", $this->data[way]);
  }

  function members() {
    return array();
  }

  function get_centre() {
    return array();
  }
}

class place_node extends place {
  function get_type() { return "node"; }
  function __construct($data) {
    parent::__construct($data);
  }

  function get_xml($obj, $root, $inc_children=0, $bounds=0) {
    $obj->setAttribute("way", $this->data[way]);
//    $obj->setAttribute("lat", $this->data[lat]);
//    $obj->setAttribute("lon", $this->data[lon]);
  }

  function members()  {
    return array();
  }

  function get_centre() {
    $res=sql_query("select X(way) as lon, Y(way) as lat from (select ST_Centroid(load_geo('{$this->data['element']}_{$this->data['id']}')) as way) x");
    $elem=pg_fetch_assoc($res);
    return $elem;
  }

  function get_ways() {
    $ret=array();

    $res=sql_query("select * from way_nodes where node_id='{$this->data[id]}'");
    while($elem=pg_fetch_assoc($res)) {
      $ret[]="way_".$elem[way_id];
    }

    return $ret;
  }
}

class place_gen_node extends place {
  function get_type() { return "node"; }
  function __construct($data) {
    parent::__construct($data);
  }

  function get_xml($obj, $root, $inc_children=0, $bounds=0) {
    $pos=$this->get_centre();

//    $obj->setAttribute("lat", $this->data[lat]);
//    $obj->setAttribute("lon", $this->data[lon]);
    $obj->setAttribute("way", "POINT($pos[lon] $pos[lat])");
  }

  function members()  {
    return array();
  }

  function get_centre() {
    $res=sql_query("select node_id, \"addr:housenumber\", lon, lat from way_nodes join planet_osm_nodes on way_nodes.node_id=planet_osm_nodes.id left join planet_osm_point on planet_osm_nodes.id=osm_id where way_id='{$this->data[id]}' order by sequence_id");
    $list=array();
    while($elem=pg_fetch_assoc($res)) {
      $list[]=$elem;
    }

    $list[0][pos]=0;
    $length=0;
    for($i=1; $i<sizeof($list); $i++) {
      $l=sqrt(pow($list[$i][lon]-$list[$i-1][lon], 2)+pow($list[$i][lat]-$list[$i-1][lat], 2));
      $length+=$l;
      $list[$i][pos]=$length;
      $list[$i][length]=$l;
    }

    $min_num=$list[0]["addr:housenumber"];
    $max_num=$list[sizeof($list)-1]["addr:housenumber"];

    $pos=($this->data[subid]-$min_num)/($max_num-$min_num);
    if(($pos<0)||($pos>1))
      return;
   
    $pos=$pos*$length;

    for($i=1; $i<sizeof($list); $i++) {
      if($list[$i][pos]>$pos) {
	$lon=$list[$i-1][lon]+($list[$i][lon]-$list[$i-1][lon])*($pos/$list[$i][length]);
	$lat=$list[$i-1][lat]+($list[$i][lat]-$list[$i-1][lat])*($pos/$list[$i][length]);
	return array("lon"=>$lon, "lat"=>$lat);
      }
      $pos-=$list[$i][length];
    }

  }
}

class place_way extends place {
  function get_type() { return "way"; }
  function __construct($data) {
    parent::__construct($data);
    /*
    if(is_string($this->data[nodes])) {
      $this->data[nodes]=parse_array($this->data[nodes]);
      foreach($this->data[nodes] as $mem_id) {
        $obj=load_object("node_".$mem_id);
        $this->members[]=array($obj, null);
      }
    }
    */
  }

  function get_xml($obj, $root, $inc_children=0, $bounds=0) {
    $obj->setAttribute("way", $this->data[way]);
/*
    foreach($this->members as $mem) {
      $x=$root->createElement("nd");
      $id=explode("_", $mem[0]->id);
      $x->setAttribute("ref", $id[1]);
      $obj->appendChild($x);
    }

    foreach($this->members as $mem) {
      $mem[0]->get_xml($obj->parentNode, $root);
    }
*/
  }

  function get_centre() {
    $res=sql_query("select X(way) as lon, Y(way) as lat from (select ST_Centroid(load_geo('{$this->data['element']}_{$this->data['id']}')) as way) x");
    $elem=pg_fetch_assoc($res);
    return $elem;
  }

  function get_nodes() {
    $ret=array();

    $res=sql_query("select * from way_nodes where way_id='{$this->data[id]}'");
    while($elem=pg_fetch_assoc($res)) {
      $ret[]="node_".$elem[node_id];
    }

    return $ret;
  }
}

class place_rel extends place {
  function get_type() { return "rel"; }
  public $members=array();

  function __construct($data) {
    parent::__construct($data);
    if(is_string($this->data[member_ids])) {
      $this->data[member_ids]=parse_array($this->data[member_ids]);
      $this->data[member_roles]=parse_array($this->data[member_roles]);
      load_objects($this->data[member_ids]);
      foreach($this->data[member_ids] as $i=>$mem) {
        $obj=load_object($mem);
        $this->members[]=array($obj, $this->data[member_roles][$i]);
      }
    }
  }

  function get_xml($obj, $root, $inc_children=0, $bounds=0) {
    foreach($this->members as $mem) {
      $x=$root->createElement("member");
      $id=explode("_", $mem[0]->id);
      $x->setAttribute("type", $id[0]);
      $x->setAttribute("ref", $id[1]);
      $x->setAttribute("role", $mem[1]);
      $obj->appendChild($x);
    }

    if($inc_children) {
      foreach($this->members as $mem) {
	if(in_bounds($mem, $bounds)) {
	  $mem[0]->get_xml($obj->parentNode, $root);
	}
      }
    }
  }

  function members() {
    return $this->members;
  }

  function find_member_role($ob) {
    foreach($this->members() as $m) {
      if($m[0]==$ob) {
	return $m[1];
      }
    }

    return null;
  }

  function get_centre() {
    $res=sql_query("select rm.relation_id, astext(ST_Centroid(ST_Collect(CASE WHEN p.way is not null THEN p.way WHEN l.way is not null THEN l.way WHEN po.way is not null THEN po.way WHEN mp.way is not null THEN mp.way END))) as way from relation_members rm left join planet_osm_point p on rm.member_id=p.osm_id and rm.member_type='N' left join planet_osm_line l on rm.member_id=l.osm_id and rm.member_type='W' left join planet_osm_polygon po on rm.member_id=po.osm_id and rm.member_type='W' left join planet_osm_polygon mp on mp.osm_id=-rm.relation_id where relation_id='{$this->data[id]}' group by relation_id");
    $elem=pg_fetch_assoc($res);
    $bbox=bbox($elem[way]);
    return array("lon"=>$bbox[0]+($bbox[2]-$bbox[0])/2,
                 "lat"=>$bbox[1]+($bbox[3]-$bbox[1])/2);
  }
}

class place_coll extends place {
  function get_type() { return "coll"; }
  public $members=array();

  function __construct($data) {
    parent::__construct($data);
    if(is_string($this->data[member_ids])) {
      $this->data[member_ids]=parse_array($this->data[member_ids]);
      $this->data[member_roles]=parse_array($this->data[member_roles]);
      load_objects($this->data[member_ids]);
      foreach($this->data[member_ids] as $i=>$mem) {
        $obj=load_object($mem);
        $this->members[]=array($obj, $this->data[member_roles][$i]);
      }
    }
  }

  function get_xml($obj, $root, $inc_children=0, $bounds=0) {
    foreach($this->members as $mem) {
      $x=$root->createElement("member");
      $id=explode("_", $mem[0]->id);
      $x->setAttribute("type", $id[0]);
      $x->setAttribute("ref", $id[1]);
      $obj->appendChild($x);
    }

    if($inc_children) {
      foreach($this->members as $mem) {
	$mem[0]->get_xml($obj->parentNode, $root);
      }
    }
  }

  function members() {
    return $this->members;
  }

  function find_member($ob) {
    foreach($this->members() as $m) {
      if($m==$ob) {
	return $m;
      }
    }

    return null;
  }
}

function places_geometry($list) {
  $nlist=array();
  foreach($list as $l) {
    $ob=load_object($l);
    if(($ob)&&($ob->tags->get("place"))) {
	$nlist[]=$ob->only_id;
      }
  }
  $list=$nlist;

  if(!sizeof($list))
    return;

  $res=sql_query("select id_place_node as id, astext(area) as area from planet_osm_place where id_place_node in ('".implode("', '", $list)."') and area is not null");
  while($elem=pg_fetch_assoc($res)) {
    $ob=load_object("node_".$elem[id]);
    $ob->place()->data[way]=postgis_collect($ob->place()->data[way], $elem[area]);
  }

  function get_centre() {
    $res=sql_query("select rm.coll_id, astext(ST_Centroid(ST_Collect(CASE WHEN p.way is not null THEN p.way WHEN l.way is not null THEN l.way WHEN po.way is not null THEN po.way WHEN mp.way is not null THEN mp.way END))) as way from coll_members rm left join planet_osm_point p on rm.member_id=p.osm_id and rm.member_type='N' left join planet_osm_line l on rm.member_id=l.osm_id and rm.member_type='W' left join planet_osm_polygon po on rm.member_id=po.osm_id and rm.member_type='W' left join planet_osm_polygon mp on mp.osm_id=-rm.coll_id where coll_id='{$this->data[id]}' group by coll_id");
    $elem=pg_fetch_assoc($res);
    $bbox=bbox($elem[way]);
    return array("lon"=>$bbox[0]+($bbox[2]-$bbox[0])/2,
                 "lat"=>$bbox[1]+($bbox[3]-$bbox[1])/2);
  }
}

register_hook("modify_geometry", places_geometry);
