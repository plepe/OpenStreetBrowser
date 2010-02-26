<?
class rule {
  function __construct($dom) {
    $this->tags=new tags();
    $this->tags->readDOM($dom);
  }
}

class category {
  function __construct($id) {
    global $lists_dir;
    $this->id=$id;
    $this->rules=array();

    if(!file_exists("$lists_dir/$this->id.xml"))
      return null;

    $this->text=category_load($id);
    $this->data=new DOMDocument();
    $this->data->loadXML($this->text);

    $rules=$this->data->getElementsByTagName("rule");
    for($i=0; $i<$rules->length; $i++) {
      $this->rules[$rules->item($i)->getAttribute("id")]=
        new rule($rules->item($i));
    }
  }

  function get_list($param) {
    global $request;
    global $importance;
    global $postgis_tables;
    global $lists_dir;

    // load category configuration
    if(!file_exists("$lists_dir/$this->id.xml.save"))
      return null;

    $list_data=unserialize(file_get_contents("$lists_dir/$this->id.xml.save"));

  //// process params ////
    // count
    $count=10;
    if($param['count'])
      $count=$param['count'];

    // exclude
    if($param['exclude']) {
      $excl_list=explode(",", $param['exclude']);
      $exclude_list=array();
      foreach($excl_list as $e) {
	if(ereg("(node|way|rel|coll)_([0-9]*)", $e, $m))
	  $exclude_list[$m[1]][]=$m[2];
      }

      foreach($exclude_list as $type=>$excl_list) {
	$exclude_list[$type]="{$type}_id not in (".implode(", ", $excl_list).")";
      }

      foreach($postgis_tables as $type=>$type_conf) {
	if(is_array($type_conf[id_type])) {
	  foreach($type_conf[id_type] as $id_type)
	    if($exclude_list[$id_type])
	      $sql_where[$type][]=$exclude_list[$id_type];
	}
	else {
	  if($exclude_list[$type_conf[id_type]])
	    $sql_where[$type][]=$exclude_list[$type_conf[id_type]];
	}
      }
    }

    // viewbox
    if($param[viewbox]) {
      $coord=explode(",", $param[viewbox]);
      $sql_where['*'][]="geo&&PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913) and Intersects(SnapToGrid(geo, 0.00001), PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913))";
    }

  //// set some more vars
    $max_count=$count+1;
    $list=array();

  //// now run, until we are finished
    foreach($importance as $imp) {
      if(($max_count>0)&&($list_data[$imp])) {
	foreach($list_data[$imp] as $t=>$req_data) {
	  $qry_where=array();
	  if(sizeof($sql_where[$t]))
	    $qry_where[]=implode(" and ", $sql_where[$t]);
	  if(sizeof($sql_where['*']))
	    $qry_where[]=implode(" and ", $sql_where['*']);

	  $req_where=array();
	  if($req_data[where])
	    $req_where[]=implode(" or ", $req_data[where]);

	  if(is_array($req_data[where_imp])) {
	    if(sizeof($req_data[where_imp]))
	      $req_where[]="(".implode(" or ", $req_data[where_imp]).") and \"importance\"='$imp'";
	    else
	      $req_where[]="\"importance\"='$imp'";
	  }

	  if(sizeof($req_where))
	    $req_where="where ".implode(" or ", $req_where);
	  else
	    $req_where="";

	  $where=implode(" and ", $qry_where);

	  $qryc="select *, astext(ST_Centroid(geo)) as center from (";
	  $qryc.=$req_data[sql];
	  $qryc.=") as x where $where limit $max_count";
	  //print "==$qryc==";
	  
	  $resc=sql_query($qryc);
	  $max_count-=pg_num_rows($resc);
	  while($elemc=pg_fetch_assoc($resc))
	    $list[]=$elemc;
	}
      }
    }

  //  if($max_count>0) {
  //    $qryc="select * from (select 'rel' as type, id, (CASE {$request[gastro][suburban]} END) as res from planet_osm_point as t1) as t where res is not null limit $max_count";
  //    $resc=sql_query($qryc);
  //    $max_count-=pg_num_rows($resc);
  //    while($elemc=pg_fetch_assoc($resc))
  //      $list[]=$elemc;
  //  }

    $more=0;
    if(sizeof($list)>$count) {
      $list=array_slice($list, 0, $count);
      $more=1;
    }

    $ret ="<category id='$this->id'";
    $ret.=" complete='".($more?"false":"true")."'";
    $ret.=">\n";
    foreach($list as $l) {
      $ret.=$this->print_match($l);
    }
    $ret.="</category>\n";

    return $ret;
  }

  function print_match($res) {
    global $lang;
    $id=$res[id];
    $rule=$this->rules[$res[rule_id]];

    $ret="<match ";
    $ob=load_object($id);
    $info=explode("||", $res[res]);
    $make_valid=array("&"=>"&amp;", "\""=>"&quot;", "<"=>"&lt;", ">"=>"&gt;");

    $ret.="id=\"$id\" ";
    $ret.="rule_id=\"$res[rule_id]\">\n";

    $ret.="  <tag k=\"geo:center\" v=\"$res[center]\"/>\n";

//    if(!($name_def=$rule->tag->get("display_name"))) {
//      $name_def="ref - name;name;ref;operator";
//    }
//
    if($x=$ob->long_name()) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"name\" v=\"$x\"/>\n";
    }

    if($x=$ob->long_name($lang)) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"name_trans\" v=\"$x\"/>\n";
    }

    if($x=$ob->tags->get("$info[0]")) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"description\" v=\"$x\"/>\n";
    }

    if($x=$ob->tags->get("$info[0]:$lang")) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"description_trans\" v=\"$x\"/>\n";
    }
    elseif($x=$lang_str["$info[0]=".$ob->tags->get("$info[0]")]) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"description_trans\" v=\"$x\"/>\n";
    }

    if($x=$info[1]) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"icon\" v=\"$x\"/>\n";
    }

    if($x=$info[2]) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"data\" v=\"$x\"/>\n";
    }

    $ret.="</match>\n";

    return $ret;
  }
}
