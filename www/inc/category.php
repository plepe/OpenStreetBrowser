<?
$make_valid=array("&"=>"&amp;", "\""=>"&quot;", "<"=>"&lt;", ">"=>"&gt;");

class category {
  function __construct($id) {
    global $lists_dir;
    $this->id=$id;
    $this->file="$lists_dir/$this->id.xml";

    if(!file_exists($this->file))
      return null;

    $this->get_tags();
  }

  function get_tags() {
    if(file_exists("$this->file.save")) {
      // if category has been compiled get tags from data
      $this->get_data();
      $this->tags=$this->data['_']['tags'];
    }
    else {
      // if category has not been compiled yet, than read the tags at least
      $this->text=category_load($this->id);
      $this->dom=new DOMDocument();
      $this->dom->loadXML($this->text);

      $this->tags=new tags();
      $this->tags->readDOM($this->dom);

      $this->data=array('_'=>array(
	"id"=>$this->id,
	"tags"=>$this->tags,
	"version"=>0,
      ));
    }
}

  function get_newest_version() {
    global $lists_dir;

    $f=popen("cd $lists_dir ; git log $this->id.xml", "r");
    $r=fgets($f);
    pclose($f);

    if(preg_match("/commit (.*)$/", $r, $m)) {
      return $m[1];
    }

    print "Error parsing git-log: $r\n";
    return null;
  }

  function get_data() {
    global $lists_dir;

    // load category configuration
    if(file_exists("$this->file.save")) {
      $this->data=unserialize(file_get_contents("$lists_dir/$this->id.xml.save"));
      return $this->data;
    }

    if(file_exists("$lists_dir/$this->id.xml")) {
      $this->data=$this->compile();
      return $this->data;
    }
    else
      return null;
  }

  function compile() {
    global $lists_dir;

    // Load content
    $this->text=category_load($this->id);
    $this->dom=new DOMDocument();
    $this->dom->loadXML($this->text);

    $this->tags=new tags();
    $this->tags->readDOM($this->dom);

    // First, create all file content
    $cur=$this->dom->firstChild;

    while($cur) {
      if($cur->nodeName=="category") {
	$cat=new process_category($this, $cur);
	$data=$cat->process();
      }
      $cur=$cur->nextSibling;
    }

    if(!$data['_'])
      $data['_']=array();
    $data['_']=array_merge($data['_'], array(
      "id"=>$this->id,
      "tags"=>$this->tags,
      "version"=>$this->get_newest_version(),
    ));

    $mapnik=build_mapnik_style($this->id, $data, $this->tags);
    $renderd=build_renderd_config($this->id, $data, $this->tags);

    // ... then write all files at once
    sql_query($data['_']['classify_fun']);

    $f1=fopen("$this->file.save", "w");
    fwrite($f1, serialize($data));
    fclose($f1);

    $f2=fopen("$this->file.mapnik", "w");
    fwrite($f2, $mapnik);
    fclose($f2);

    $f3=fopen("$this->file.renderd", "w");
    fwrite($f3, $renderd);
    fclose($f3);

    return $data;
  }

  function get_list($param) {
    global $request;
    global $importance;
    global $postgis_tables;
    global $lists_dir;

    $list_data=$this->data;
    if(!$this->data['_']['version']) {
      $ret ="<category id='$this->id'";
      $ret.=" status='not_compiled'";
      $ret.=">\n";
      foreach($list as $l) {
	$ret.=$this->print_match($l);
      }
      $ret.="</category>\n";

      return $ret;
    }

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
	if(ereg("^(node|way|rel|coll)_([0-9]*)$", $e, $m))
	  $exclude_list[]=$m[0];
      }

      $sql_where['*'][]="osm_id not in ('".implode("', '", $exclude_list)."')";

/*      foreach($exclude_list as $type=>$excl_list) {
	$exclude_list[$type]=" not in (".implode(", ", $excl_list).")";
      }

      foreach($postgis_tables as $type=>$type_conf) {
	if(is_array($type_conf[id_type])) {
	  foreach($type_conf[id_type] as $id_type)
	    if($exclude_list[$id_type])
	      $excl_where[$type]=$type_conf[id_name]." ".$exclude_list[$id_type];
	}
	else {
	  if($exclude_list[$type_conf[id_type]])
	    $excl_where[$type]=$type_conf[id_name]." ".$exclude_list[$type_conf[id_type]];
	}
      } */
    }

    // viewbox
    if($param[viewbox]) {
      $coord=explode(",", $param[viewbox]);
      $sql_where['*'][]="Intersects(SnapToGrid(geo, 0.00001), PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913))";
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
	  
	  if(!$where)
	    $where="true";

          $sql=strtr($req_data[sql], array(
	    "!bbox!"=>"PolyFromText('POLYGON(($coord[0] $coord[1], $coord[2] $coord[1], $coord[2] $coord[3], $coord[0] $coord[3], $coord[0] $coord[1]))', 900913)",
	  ));

	  $qryc="select *, astext(ST_Centroid(geo)) as center from (";
	  $qryc.=$sql;
	  $qryc.=") as x where $where limit $max_count";
	  //print "==\n$qryc\n==";
	  
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
    $ret.=" version='{$this->data['_']['version']}'";
    
    if($this->get_newest_version()!=$this->data['_']['version']) {
      $ret.=" status='old_version'";
    }

    $ret.=" complete='".($more?"false":"true")."'";
    $ret.=">\n";
    foreach($list as $l) {
      $ret.=$this->print_match($l);
    }
    $ret.="</category>\n";

    return $ret;
  }

  function print_match($res) {
    global $data_lang;
    $lang=$data_lang;
    $id=array();

    global $make_valid;
    $id=$res['osm_id'];

    $rule_tags=new tags(parse_hstore($res['rule_tags']));

    $tags=parse_hstore($res['osm_tags']);

    $ret="<match ";
    $ob=load_object($res, $tags);
    $info=explode("||", $res[res]);

    $ret.="id=\"$id\" ";
    $ret.="rule_id=\"$res[rule_id]\">\n";

    $ret.="  <tag k=\"geo:center\" v=\"$res[center]\"/>\n";

    if($x=$ob->tags->parse($rule_tags->get("display_name"), $lang)) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"display_name:$lang\" v=\"$x\"/>\n";
    }

    if($x=$ob->tags->parse($rule_tags->get("display_name"))) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"display_name\" v=\"$x\"/>\n";
    }

    if($x=$ob->tags->parse($rule_tags->get("display_type"), $lang)) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"display_type:$lang\" v=\"$x\"/>\n";
    }

    if($x=$ob->tags->parse($rule_tags->get("display_type"))) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"display_type\" v=\"$x\"/>\n";
    }

    if($x=$rule_tags->get("icon")) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"icon\" v=\"".get_icon($x)."\"/>\n";
    }

    if($x=$rule_tags->get("importance")) {
      $x=strtr($x, $make_valid);
      $ret.="  <tag k=\"importance\" v=\"$x\"/>\n";
    }

    $ret.="</match>\n";

    return $ret;
  }
}
