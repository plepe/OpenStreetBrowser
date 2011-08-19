<?
$class_info=0;
function classes_match($value1, $operator, $value2) {
  $match=1;

  switch($operator) {
    case "=":
      if($value1!=$value2)
	$match=0;
      break;
    case ">=":
      if($value1<$value2)
	$match=0;
      break;
    case "<=":
      if($value1>$value2)
	$match=0;
      break;
    case ">":
      if($value1<=$value2)
	$match=0;
      break;
    case "<":
      if($value1>=$value2)
	$match=0;
      break;
    case "<>":
    case "!=":
      if($value1=$value2)
	$match=0;
      break;
  }

  return $match;
}

function load_classes($file, $bounds) {
  global $class_info;
  global $root_path;

//  if(!is_array($keys))
//    $keys=array($keys);
  if(!$class_info)
    $class_info=array();

  $f=fopen("$root_path/www/plugins/basemap/$file.mss", "r");
  $this_style_query=array();
  $mode=0;

  $list=array();
  while($r=fgets($f)) {
    $r=trim($r);
    $notdone=2;
    while($notdone) {
//	$ret.="r is =$r= $mode $notdone<br>\n";
      $notdone=0;

      if($mode==0) {
	if(eregi("\.([a-z0-9_]*)(\[.*\])? ?([a-z_0-9]*)?[,]?", $r, $m)) {
	  $key=$m[1];
	  $query=explode("][", substr($m[2], 1, -1));
	  $column=$m[3];

	  $r=trim(substr($r, strlen($m[0])));
	  if($r) $notdone=1;

//	  $match=0;
//	  if(in_array($key, $keys))
	    $match=1;

	  $new_query=array();
	  foreach($query as $q) {
	    if(eregi("^zoom(=|<>|\!=|>=|<=|>|<)([^=<>\!]*)$", $q, $m)) {
	      if(!classes_match($bounds[zoom], $m[1], $m[2]))
		$match=0;
//		$ret.="zoom $m[1] $m[2] $match<br>\n";
	    }
	    elseif(eregi("^([^=<>\!]*)(=|<>|\!=|>=|<=|>|<)([^=<>\!]*)$", $q, $m)) {
	      $new_query[]=array($m[1], $m[2], $m[3]);
	    }
	  }
	  $query=$new_query;

	  if($match)
	    $this_style_query[]=array($key, $query, $column);
	  
	}
	elseif(eregi("^\{", $r, $m)) {
	  $mode=1;
	  $style=array();

	  $r=trim(substr($r, strlen($m[0])));
	  if($r) $notdone=1;
	}
      }
      elseif($mode==1) {
	if(eregi("^\}", $r, $m)) {
	  $mode=0;
	  $r=trim(substr($r, strlen($m[0])));
	  if($r) $notdone=1;

          foreach($this_style_query as $q) {
	    if(!$class_info[$q[0]])
	      $class_info[$q[0]]=array("keys"=>array(), "styles"=>array());
	    $dep_list=array();
	    foreach($q[1] as $q1) {
	      if(!$class_info[$q[0]]["keys"][$q1[0]])
		$class_info[$q[0]]["keys"][$q1[0]]=array();
	      if(!in_array("$q1[1]$q1[2]", $class_info[$q[0]]["keys"][$q1[0]]))
		$class_info[$q[0]]["keys"][$q1[0]][]="$q1[1]$q1[2]";
	      $dep_list[]="$q1[0]$q1[1]$q1[2]";
	    }
//	    $ds=array();
//	    foreach($keys as $k=>$dummy) {
//	      if($dep_list[$k])
//		$ds[]=$dep_list[$k];
//	    }
//	    $dep_list=$ds;
            sort($dep_list);
	    $dep_list=implode("|", $dep_list);
	    if(!$class_info[$q[0]]["styles"][$dep_list][$q[2]])
	      $class_info[$q[0]]["styles"][$dep_list][$q[2]]=array();
	    $class_info[$q[0]]["styles"][$dep_list][$q[2]]=array_merge(
	      $class_info[$q[0]]["styles"][$dep_list][$q[2]], $style);
	  }

//	  foreach($this_style_query as $q) {
//	    if(sizeof($q[1])) {
//	      if(($class_info[$q[0]][implode("", $q[1][0])])&&
//		 ($class_info[$q[0]][implode("", $q[1][0])][$q[2]]))
//		$class_info[$q[0]][implode("", $q[1][0])][$q[2]]=
//		  array_merge($class_info[$q[0]][implode("", $q[1][0])][$q[2]], $style);
//	      else
//		$class_info[$q[0]][implode("", $q[1][0])][$q[2]]=$style;
//	    }
//	    else
//	      $class_info[$q[0]]["default"][$q[2]]=$style;
//	    // TODO: only first query is saved
//	  }

	  $this_style_query=array();
	}
	elseif(eregi("^([^:;]*)[ \t]*:[ \t]*([^ ;]*);", $r, $m)) {
	  $r=trim(substr($r, strlen($m[0])));
	  if($r) $notdone=1;

	  $style[$m[1]]=$m[2];
	}
	elseif(eregi("^([^:;]*)[ \t]*:[ \t]*\"(.*)\";", $r, $m)) {
	  $r=trim(substr($r, strlen($m[0])));
	  if($r) $notdone=1;

	  $style[$m[1]]=$m[2];
	}
      }
    }
  }

  $values=array();
}


class map_key {
  function title() {
    return "Map key";
  }

  function show_mss($classes, $keys, $bounds, $options=array()) { 
    global $class_info;
//    print_r($class_info);
    if($options[geom])
      $default_geom=$options[geom];
    else
      $default_geom=array();

    $style=array();

    foreach($classes as $class_index=>$class) {
      $dep_list=array(array());
      //foreach($keys as $k=>$vs) {
      $keys_keys=array_keys($keys);
      for($i=sizeof($keys_keys)-1; $i>=0; $i--) {
	$k=$keys_keys[$i];
	$vs=$keys[$k];
        if($vs=="*") {
          $new_dep_list=array();
	  if($class_info[$class]["keys"][$k]) {
	    foreach($class_info[$class]["keys"][$k] as $v) {
	      if(!sizeof($dep_list)) {
		$new_dep_list[]=array("$k$v");
	      }
	      else {
		foreach($dep_list as $d) {
		  $new_dep_list[]=array_merge(array("$k$v"), $d);
		}
	      }
	    }
	    $dep_list=$new_dep_list;
	  }
        }
        elseif(is_string($vs)) {
          $new_dep_list=array();
          if(!sizeof($dep_list)) {
            $dep_list[]=array("$k$vs");
          }
          else {
            foreach($dep_list as $d) {
              $new_dep_list[]=array_merge($d, array("$k$vs"));
            }
            $dep_list=$new_dep_list;
          }
        }
        else {
          $new_dep_list=array();
          foreach($vs as $v) {
            if(!sizeof($dep_list)) {
              $new_dep_list[]=array("$k$v");
            }
            else {
              foreach($dep_list as $d) {
                $new_dep_list[]=array_merge(array("$k$v"), $d);
              }
            }
          }
          $dep_list=$new_dep_list;
        }

//        foreach($dep_list) {
//        sort($dep_list);
//        $dep_list=implode("|", $dep_list);
//
//        $style[$dep_list][$class]=$class_info[$class]["styles"][$dep_list];
      }
    }

    foreach($classes as $class_index=>$class) {
      //print_r($dep_list);
      foreach($dep_list as $d) {
//	$ds=array();
//	foreach($keys as $k=>$dummy) {
//	  if($d[$k])
//	    $ds[]=$d[$k];
//	}
//	$d=$ds;
        sort($d);
        $d_key=implode("|", $d);
        for($i=0; $i<pow(2, sizeof($d)); $i++) {
          $d1=array();
          for($j=0; $j<sizeof($d); $j++) {
            if(pow(2, $j)&$i) {
              $d1[]=$d[$j];
            }
          }

          $d1_key=implode("|", $d1);
	  if($class_info[$class]["styles"][$d1_key])
	  foreach($class_info[$class]["styles"][$d1_key] as $column=>$this_style) {
//            print "$class_index $d1_key\n";
	  if(!$style[$d_key][$class_index][$column])
	    $style[$d_key][$class_index][$column]=array("column"=>$column);
	    if($this_style) {
	      //print "$class_index $d1_key\n";
	      $style[$d_key][$class_index][$column]=
		array_merge($style[$d_key][$class_index][$column], $this_style);
	      //print_r($this_style);
	      //print_r($style[$d_key][$class_index]);
	    }
	  }
        }
      }
    }

    $new_style=array();
    foreach($style as $depend=>$s1) {
      foreach($s1 as $s) {
	foreach($s as $style) {
	  $new_style[$depend][]=$style;
	}
      }
    }
    $style=$new_style;

    foreach($style as $depend=>$s) {
      $p=array();
      $geom=$default_geom;

      foreach($s as $index=>$s1) {
        if($s1["line-width"])
          $geom["line"]=1;
        if($s1["line-pattern-file"]) {
	  if(preg_match("/^url\('(.*)'\)$/", $s1['line-pattern-file'], $m))
	    $s[$index]['line-pattern-file']="{$options['img_base_path']}/$m[1]";
          $geom["line"]=1;
	}
        if($s1["polygon-pattern-file"]) {
	  if(preg_match("/^url\('(.*)'\)$/", $s1['polygon-pattern-file'], $m))
	    $s[$index]['polygon-pattern-file']="{$options['img_base_path']}/$m[1]";
          $geom["poly"]=1;
	}
        if($s1["point-file"]) {
	  if(preg_match("/^url\('(.*)'\)$/", $s1['point-file'], $m))
	    $s[$index]['point-file']="{$options['img_base_path']}/$m[1]";
          $geom["point"]=1;
	}
        if($s1["polygon-fill"])
          $geom["poly"]=1;
        if($s1["point-file"])
          $geom["point"]=1;
        if($s1["text-size"])
          $geom["point"]=1;
      }

      if(sizeof($geom)) {
        $ret.="<tr><td>\n";
        build_request($s, "param", &$p);
        $param=implode("&", $p);

	if($geom["poly"])
	  $ret.="<div><embed width='30' type='image/svg+xml' src='plugins/map_key/symbol_polygon.php?$param' /></div>";
	elseif($geom["line"])
	  $ret.="<div><embed width='30' type='image/svg+xml' src='plugins/map_key/symbol_line.php?$param' /></div>";
	elseif($geom["point"])
	  $ret.="<div><embed width='30' type='image/svg+xml' src='plugins/map_key/symbol_point.php?$param' /></div>";
        $ret.="</td><td>\n";
        $ret.=lang("$options[prefix]$depend");
        $ret.="</td></tr>\n";
      }
    }

    return $ret;
  }

  function show_info($bounds) {
    global $overlays_show;
    global $class_info;
    $ret=lang("map_key_head")." (".lang("map_key:zoom")." $bounds[zoom])";

    load_classes("roads", $bounds);
    load_classes("road_features", $bounds);
    load_classes("land", $bounds);
    load_classes("boundaries", $bounds);
    load_classes("water", $bounds);
    load_classes("places", $bounds);
    load_classes("buildings", $bounds);
    load_classes("amenities", $bounds);
    load_classes("housenumbers", $bounds);

    if($bounds[overlays][ch])
      load_classes("overlay_ch", $bounds);
    if($bounds[overlays][pt])
      load_classes("overlay_pt", $bounds);
    if($bounds[overlays][car])
      load_classes("overlay_car", $bounds);
    if($bounds[overlays][culture])
      load_classes("overlay_culture", $bounds);
    if($bounds[overlays][services])
      load_classes("overlay_services", $bounds);
//    print_r($class_info);

//    $ret.=$this->show_mss("places", "places_db", "Places", $bounds);
//    $ret.=$this->show_mss("boundaries", array("world1", "admin"), "Borders", $bounds);
//    $ret.=$this->show_mss("land", array("world1", "world", "coastpoly", "landuse"), "Landuse", $bounds);
//    $ret.=$this->show_mss("water", array("waterarea", "water"), "Water", $bounds);
//    $ret.=$this->show_mss("land", array("buildings"), "Buildings", $bounds);
    $ret.="<h4>".lang("head:places")."</h4>\n";
    $ret.="<table>\n";
    $ret.=$this->show_mss(array("places_high"), 
      array("place"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("places_db"), 
      array("place"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";

    $ret.="<h4>".lang("head:roads")."</h4>\n";
    $ret.="<table>\n";
//    $ret.=$this->show_mss(array("roads_casing", "roads_fill", "roads_rail"), 
//      array("highway_type"=>"=rail", "railway"=>array("=tram", "=rail"), "tracks"=>"=single"), $bounds);
    $ret.=$this->show_mss(array("roads_extcas", "roads_extract"), 
      array("highway_type"=>array("=motorway", "=major", "=minor", "=service", "=pedestrian", "=path", "=aeroway"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("roads_casing_end", "roads_casing", "roads_fill"), 
      array("highway_type"=>array("=motorway", "=major", "=minor", "=service", "=pedestrian", "=path", "=aeroway"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("square_casing", "square_fill"), 
      array("type"=>"*"), $bounds, array("prefix"=>"square_", "img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";

    $ret.="<h4>".lang("head:rails")."</h4>\n";
    $ret.="<table>\n";
    $ret.=$this->show_mss(array("roads_extract"), 
      array("highway_type"=>"=railway", "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("roads_casing_end", "roads_casing", "roads_fill"), 
      array("highway_type"=>"=railway", "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("roads_rail"), 
      array("railway"=>"*", "tracks"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";

    $ret.="<h4>".lang("head:goods")."</h4>\n";
    $ret.="<table>\n";
//    $ret.=$this->show_mss(array("roads_casing", "roads_fill", "roads_rail"), 
//      array("highway_type"=>"=rail", "railway"=>array("=tram", "=rail"), "tracks"=>"=single"), $bounds);
    $ret.=$this->show_mss(array("roads_extract"), 
      array("highway_type"=>array("=power", "=pipeline"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("roads_casing_end", "roads_casing", "roads_fill"), 
      array("highway_type"=>array("=power", "=pipeline"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";

    $ret.="<h4>".lang("head:borders")."</h4>\n";
    $ret.="<table>\n";
    $ret.=$this->show_mss(array("world1"), 
      array(), $bounds, array("prefix"=>"admin_level=2", "img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("admin"), 
      array("admin_level"=>array("=2", "=3", "=4", "=5", "=6", "=8", "=10")), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";

    $ret.="<h4>".lang("head:landuse")."</h4>\n";
    $ret.="<table>\n";
    $ret.=$this->show_mss(array("landuse_extract"), 
      array("landuse"=>"*"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("landuse_extract"), 
      array("landuse"=>"=natural", "sub_type"=>"*"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("landuse"), 
      array("landuse"=>"*"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("landuse"), 
      array("landuse"=>"=natural", "sub_type"=>"*"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("waterarea"), 
      array("landuse"=>"=water"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";
    
    $ret.="<h4>".lang("head:buildings")."</h4>\n";
    $ret.="<table>\n";
    $ret.=$this->show_mss(array("buildings"), 
      array("building"=>"*"), $bounds, array("geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap"));
//    $ret.=$this->show_mss(array("amenity"), 
//      array("type"=>"*", "sub_type"=>"*"), $bounds);
    $ret.="</table>\n";

    $ret.="<h4>".lang("head:housenumbers")."</h4>\n";
    $ret.="<table>\n";
    $ret.=$this->show_mss(array("housenumbers"), 
      array(), $bounds, array("prefix"=>"housenumber", "img_base_path"=>"plugins/basemap"));
    $ret.=$this->show_mss(array("housenumber_lines"), 
      array(), $bounds, array("prefix"=>"housenumber_lines"), array("img_base_path"=>"plugins/basemap"));
    $ret.="</table>\n";

    if($bounds[overlays][pt]) {
      $ret.="<h4>".lang("head:pt")."</h4>\n";
      $ret.="<table>\n";

      $ret.=$this->show_mss(array("routes", "routestext"), 
	array("route"=>array("=rail", "=subway", "=tram", "=ferry", "=bus", "=tram_bus"), "network"=>"*"), $bounds);

      $ret.=$this->show_mss(array("stations_top", "stations_center"),
	array("network"=>"*"), $bounds);

      $ret.="</table>\n";
    }

    if($bounds[overlays][culture]) {
      $ret.="<h4>".lang("head:culture")."</h4>\n";
      $ret.="<table>\n";

      $ret.=$this->show_mss(array("amenity_culture"),
	array("type"=>"*", "sub_type"=>"*"), $bounds);

      $ret.="</table>\n";
    }

    if($bounds[overlays][services]) {
      $ret.="<h4>".lang("head:services")."</h4>\n";
      $ret.="<table>\n";

      $ret.=$this->show_mss(array("amenity_services"),
	array("type"=>"*", "sub_type"=>"*"), $bounds);

      $ret.="</table>\n";
    }
//    $ret.=$this->show_mss("watername", array("buildings"), "Buildings", $bounds);
//    $ret.=$this->show_mss("overlay_pt", "routes", "PT Routes", $bounds);
//    $ret.=$this->show_mss("overlay_pt", "stations", "PT Stops", $bounds);
    
    if($bounds[overlays][car]) {
      $ret.="<h4>".lang("head:car")."</h4>\n";
      $ret.="<table>\n";

      $ret.=$this->show_mss(array("amenity_car"),
	array("amenity"=>"*"), $bounds);

      $ret.="</table>\n";
    }

    if($bounds[overlays][ch]) {
      $ret.="<h4>".lang("head:ch")."</h4>\n";
      $ret.="<table>\n";

      $ret.=$this->show_mss(array("amenity_services"),
	array("type"=>"*", "sub_type"=>"*"), $bounds);

      $ret.="</table>\n";
    }

    return $ret;
  }

}

function map_key_main_links($links) {
  $links[]=array(-5, "<a href='javascript:map_key_toggle()'>".lang("main:map_key")."</a>");
}

register_hook("main_links", "map_key_main_links");
