<?
$map_key_cascadenik_aliases=array();
$map_key_cascadenik_hide=array();

class map_key_cascadenik extends map_key_entry {
  function show_mss($classes, $keys, $bounds, $options=array()) { 
    global $class_info;
    global $map_key_cascadenik_aliases;
    global $map_key_cascadenik_hide;

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
        //rsort($d);
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

      if($map_key_cascadenik_hide[$depend])
        continue;

      if(sizeof($geom)) {
        $ret.="<tr><td>\n";
        build_request($s, "param", &$p);
        $param=implode("&", $p);

	if($geom["poly"])
	  $ret.="<div><embed width='30' height='12' type='image/svg+xml' src='plugins/map_key/symbol_polygon.php?$param' /></div>";
	elseif($geom["line"])
	  $ret.="<div><embed width='30' height='12' type='image/svg+xml' src='plugins/map_key/symbol_line.php?$param' /></div>";
	elseif($geom["point"])
	  $ret.="<div><embed width='30' height='30' type='image/svg+xml' src='plugins/map_key/symbol_point.php?$param' /></div>";
        $ret.="</td><td>\n";

        // Compile tag-text
        if(!($tag=$map_key_cascadenik_aliases[$depend]))
          $tag=$depend;
        $tag=explode("|", $tag);
        for($i=0; $i<sizeof($tag); $i++)
          $tag[$i]=lang("tag:{$tag[$i]}");
        $tag=implode(", ", $tag);

        $ret.=$tag;
        $ret.="</td></tr>\n";
      }
    }

    return $ret;
  }




}

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
  global $map_key_cascadenik_aliases;
  global $map_key_cascadenik_hide;
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

    if(preg_match("/^alias\s+([^ ]*)\s+(.*)$/", $r, $m)) {
      $map_key_cascadenik_aliases[$m[1]]=$m[2];
      continue;
    }

    if(preg_match("/^hide\s+([^ ]*)$/", $r, $m)) {
      $map_key_cascadenik_hide[$m[1]]=true;
      continue;
    }

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
            //rsort($dep_list);
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
