<?
function route_info($ret, $object) {
  global $route_types;
  global $network_names;

  if($object->tags->get("type")!="route")
    return;

  $tags=$object->tags;

  switch($tags->get("route")) {
    case "bicycle":
    case "hiking":
    case "foot":
    case "mtb":
      show_overlay("ch");
      break;
    case "road":
      show_overlay("car");
      break;
    case "train":
    case "subway":
    case "railway":
    case "rail":
    case "tram":
    case "bus":
    case "minibus":
    case "ferry":
    case "trolley":
      show_overlay("pt");
      break;
  }

  $ret[]=array("general_info", $tags->compile_text("#tag_route_type#: %route%<br />\n"));
  //$ret.="Network Type: ".$this->data[network]."<br />\n";
  // TODO - field network in db muss nicht gleich tag sein

  $ret[]=array("general_info", $tags->compile_text("#tag_links#: <a href='%website%'>#field_website#</a><br />\n"));
  $ret[]=array("general_info", $tags->compile_text("#tag_state#: %state%<br />\n"));
  $ret[]=array("general_info", $tags->compile_text("#tag_symbol#: %symbol%<br />\n"));
  $ret[]=array("general_info", $tags->compile_text("#tag_description#: %description%<br />\n"));

  $text="";
  $res_i=sql_query("select * from planet_osm_rels join relation_members on planet_osm_rels.id=relation_members.relation_id and relation_members.member_type='3' where '{$object->data["id"]}'=relation_members.member_id and type='network'");
  if(pg_num_rows($res_i))
    $text.="This route is part of the networks:\n";
  while($elem_i=pg_fetch_assoc($res_i)) {
    $obj=load_object("rel_$elem_i[id]");
    $text.=list_entry($obj->id, $obj->long_name());
  }

  $text="";
  $stop_list=array();
  foreach($object->place()->members as $mem) {
    $id=$mem[0]->id;
    $role=$mem[1];

    if(eregi("^stop.*$", $role, $m)) {
      $stop_index[$id]=sizeof($stop_list);
      $stop_list[$id]=array("dir"=>0, "id"=>$id);
      $stop_id_list[]=$mem[0]->only_id;
      $load_list[]=$id;
    }
    elseif(eregi("^forward[:_]stop.*$", $role, $m)) {
      $stop_index[$id]=sizeof($stop_list);
      $stop_list[$id]=array("dir"=>1, "id"=>$id);
      $stop_id_list[]=$mem[0]->only_id;
      $load_list[]=$id;
    }
    elseif(eregi("^backward[:_]stop.*$", $role, $m)) {
      $stop_index[$id]=sizeof($stop_list);
      $stop_list[$id]=array("dir"=>-1, "id"=>$id);
      $stop_id_list[]=$mem[0]->only_id;
      $load_list[]=$id;
    }
  }

  foreach($stop_list as $i=>$stop) {
    $stop_list[$i]["possible_-1"]=array();
    $stop_list[$i]["possible_1"]=array();
    $stop_list[$i]["come_-1"]=array();
    $stop_list[$i]["come_1"]=array();
  }

  $res=sql_query("select 'way_'||l.osm_id as way_id, 'node_'||p.osm_id as stop_id, (select sequence_id from way_nodes wn join planet_osm_nodes nodes on wn.node_id=nodes.id where wn.way_id=l.osm_id order by Distance(p.way, geometryfromtext('POINT('||nodes.lon||' '||nodes.lat||')', 900913)) asc limit 1) as pos, Distance(p.way, l.way) as d from planet_osm_point p join planet_osm_line l on geometryfromtext('POLYGON(('||".
    "XMIN(p.way)-50||' '||YMIN(p.way)-50||','||".
    "XMAX(p.way)+50||' '||YMIN(p.way)-50||','||".
    "XMAX(p.way)+50||' '||YMAX(p.way)+50||','||".
    "XMIN(p.way)-50||' '||YMAX(p.way)+50||','||".
    "XMIN(p.way)-50||' '||YMIN(p.way)-50||'))', 900913)&&l.way and Distance(p.way, l.way)<50 join relation_members rm on l.osm_id=rm.member_id and rm.member_type=2 where rm.relation_id='{$object->only_id}' and p.osm_id in (".implode(",", $stop_id_list).")");
  while($elem=pg_fetch_assoc($res)) {
    $stop_list[$elem[stop_id]][ways][]=$elem[way_id];
    $way_stop_list[$elem[way_id]][$elem[stop_id]]=$elem;
  }

  $res=sql_query("select 'way_'||member_id as way_id, 'node_'||(select node_id from way_nodes where way_id=member_id and sequence_id=0) as first, 'node_'||(select node_id from way_nodes where way_id=member_id order by sequence_id desc limit 1) as last from relation_members rm where relation_id='{$object->only_id}' and member_type='2'");
  while($elem=pg_fetch_assoc($res)) {
    $ways[$elem[way_id]]=$elem;
    $nodes[$elem[first]][]=$elem[way_id];
    $nodes[$elem[last]][]=$elem[way_id];
  }
  
  $data=array(
    "stop_list"=>$stop_list,
    "stop_index"=>$stop_index,
    "ways"=>$ways,
    "nodes"=>$nodes,
    "way_stop_list"=>$way_stop_list);

  function possible_way($stop0, &$data, $rek=array()) {
    $ret=array();
//    print "rek ".implode(",", $rek)."\n";

    if(!$stop0[ways])
      return array();
    foreach($stop0[ways] as $way0) {
      if(!in_array($way0, $rek)) {
	if(isset($stop0[pos]))
	  $pos0=$stop0[pos];
	else
	  $pos0=$data[way_stop_list][$way0][$stop0[id]][pos];
	$poss=array();
	if($data[way_stop_list][$way0])
	foreach($data[way_stop_list][$way0] as $poss_stop) {
	  $stopo=$data[stop_list][$data[stop_index][$poss_stop[stop_id]]];
	  if(($stop0["dir"]==1)&&($poss_stop["pos"]>$pos0)&&($stopo["dir"]!=-1))
	    $poss[$poss_stop["pos"]]=$poss_stop;
	  if(($stop0["dir"]==-1)&&($poss_stop["pos"]<$pos0)&&($stopo["dir"]!=1))
	    $poss[$poss_stop["pos"]]=$poss_stop;
	}
	$k=array_keys($poss);
	if($stop0["dir"]==1)
	  sort($k);
	else
	  rsort($k);

	if(sizeof($poss)) {
	  $ret[]=array($poss[$k[0]][stop_id], $stop0["dir"]);
	}
	else {
	  $end=$data[ways][$way0];
	  if($stop0["dir"]==1)
	    $end=$end[last];
	  else
	    $end=$end[first];
//	  print "END";
//	  print_r($end);
//	  print_r($way0);
	  $s=array(array(
	      "dir"=>1,
	      "id"=>$end,
	      "pos"=>0
	    ), array(
	      "dir"=>-1,
	      "id"=>$end,
	      "pos"=>9999
	    ));
	  foreach($data[nodes][$end] as $w) {
	    if($w==$way0) {
	    }
	    elseif($data[ways][$w][first]==$end) {
	      $s[0][ways][]=$w;
	    }
	    else {
	      $s[1][ways][]=$w;
	    }
	  }

	  $r=$rek;
	  $r[]=$way0;
	  if($d=possible_way($s[0], $data, $r))
	    $ret=array_merge($ret, $d);
	  if($d=possible_way($s[1], $data, $r))
	    $ret=array_merge($ret, $d);
	}
      }
    }
    /*
      foreach($stop1[ways] as $way1) {
	foreach($data[way_stop_list][$way0] as $poss_stop) {
	  if($poss_stop[id]==$stop0[id]) {
	  }
	  
	  print "poss";
	  print_r($poss_stop);
	}
	if($way0==$way1) {
	  $pos0=$data[way_stop_list][$way0][$stop0[id]][pos];
	  $pos1=$data[way_stop_list][$way1][$stop1[id]][pos];
	  if(($stop0["dir"]==1)&&($stop1["dir"]!=-1))
	    return 1;
	  if(($stop0["dir"]==-1)&&($stop1["dir"]!=1))
	    return -1;
	} 
      }
    }*/

    return $ret;
  }

  foreach($stop_list as $i=>$stop) {
    $s=$stop;
    if($stop["dir"]<1) {
      $s["dir"]=-1;
      $poss=possible_way($s, $data);
      $stop_list[$i]["possible_-1"]=$poss;
      foreach($poss as $p) {
        $stop_list[$p[0]]["come_".$p[1]][]=array($stop[id], $stop["dir"]);
      }
    }
    if($stop["dir"]>-1) {
      $s["dir"]=1;
      $poss=possible_way($s, $data);
      $stop_list[$i]["possible_1"]=$poss;
      foreach($poss as $p) {
        $stop_list[$p[0]]["come_".$p[1]][]=array($stop[id], $stop["dir"]);
      }
    }
  }

print_r($stop_list);
  $turn=1;
  $last=array("possible"=>null, "come"=>null);
  foreach($stop_list as $i=>$stop) {
    $stop_ob=load_object($stop[id]);
	print $stop[id]." ".$stop_ob->tags->get("name")."\n";

    $dir=-2;
    $success_p=0;
    if($last["possible"]) {
      $dir1=0;
      $dir_1=0;

      foreach($last["possible"] as $p) {
	if($p[0]==$stop[id]) {
	  $success_p=1;
	  foreach($stop["come_-1"] as $c) {
	    if($c[0]==$last["possible_id"])
	      $dir_1=1;
	  }
	  foreach($stop["come_1"] as $c) {
	    if($c[0]==$last["possible_id"])
	      $dir1=1;
	  }
	}
      }

      if($success_p) {
	$stop_list[$last["possible_id"]]["next0"]=$stop[id];
	$stop_list[$stop[id]]["prev0"]=$last["possible_id"];
	$last["possible_id"]=$stop[id];
      }

      if($dir_1&&$dir1) {
	$dir=0;
	$last["possible"]=array_merge($stop["possible_-1"], $stop["possible_1"]);
      }
      elseif($dir_1) {
        $dir=-1;
	$last["possible"]=$stop["possible_-1"];
      }
      elseif($dir1) {
        $dir=1;
	$last["possible"]=$stop["possible_1"];
      }
    }
    else {
      $p=array_merge($stop["possible_-1"], $stop["possible_1"]);
      $last["possible"]=$p;
      $last["possible_id"]=$stop[id];
    }
      if($success_p) {
	print "YEAHp $dir!\n";
      }

    $dir=-2;
    $success_n=0;
    if($last["come"]) {
      $dir1=0;
      $dir_1=0;

      foreach($last["come"] as $p) {
	if($p[0]==$stop[id]) {
	  $success_n=1;
	  foreach($stop["possible_-1"] as $c) {
	    if($c[0]==$last["come_id"])
	      $dir1=1;
	  }
	  foreach($stop["possible_1"] as $c) {
	    if($c[0]==$last["come_id"])
	      $dir_1=1;
	  }
	}
      }

      if($success_n) {
	$stop_list[$last["come_id"]]["prev1"]=$stop[id];
	$stop_list[$stop[id]]["next1"]=$last["come_id"];
	$last["come_id"]=$stop[id];
      }

      if($dir_1&&$dir1) {
	$dir=0;
	$last["come"]=array_merge($stop["come_-1"], $stop["come_1"]);
      }
      elseif($dir_1) {
        $dir=-1;
	$last["come"]=$stop["come_1"];
      }
      elseif($dir1) {
        $dir=1;
	$last["come"]=$stop["come_-1"];
      }
    }
    else {
      $p=array_merge($stop["come_-1"], $stop["come_1"]);
      $last["come"]=$p;
      $last["come_id"]=$stop[id];
    }

//      $success=0;
//      if($last[-1][possible])
//      foreach($last[-1][possible] as $p) {
//	if($p[0]==$stop[id]) {
//	  $success=1;
//	}
//      }
//      foreach($last[1][possible] as $p) {
//	if($p[0]==$stop[id]) {
//	  $success=1;
//	}
//      }

      if($success_n) {
	print "YEAHn $dir!\n";
      }

      if((!$success_p)&&(!$success_n)) {
	$p=array_merge($stop["possible_-1"], $stop["possible_1"]);
	$last["possible"]=$p;
	$last["possible_id"]=$stop[id];
	$p=array_merge($stop["come_-1"], $stop["come_1"]);
	$last["come"]=$p;
	$last["come_id"]=$stop[id];
      }
//    if(sizeof($result)&&
//       ($result[sizeof($result)-1][name]==$stop_ob->tags->get("name"))) {
//    }
//    else {
      $res=array(
        "name"=>$stop_ob->tags->get("name"),
        "stop"=>$stop);
      print_r($last);
  }

//  print_r($result);

//	print "success {$s[id]}->$stop[id]: $dir\n";
//	$stop_list[$s[id]]["dir_$s[dir]"]=array("dir"=>$dir, "id"=>$stop[id]);
//	unset($no_next[$sp]);
//      }
//  }
  print_r($stop_list);
//  print_r($way_stop_list);
//  print_r($ways);
  return;

  load_objects($load_list);

  if(sizeof($stop_list)) {
    $stop_list_sort=array_keys($stop_list);
    natsort($stop_list_sort);

    $text.="<table>\n";
    $text.="<thead><tr><td>↓</td><td>↑</td><td></td></tr></thead>\n<tbody>";
    foreach($stop_list_sort as $num) {
      $stops=$stop_list[$num];
      $station=array(0, 0);

// TODO: find_station_rel optimieren ... Durch eine Abfrage ersetzen?
      $station=$stops;
      $stops_obj=array(load_object($stops[0]), load_object($stops[1]));
      if($stops[0]) {
	if(method_exists($stops_obj[0], "find_station"))
	  $station[0]=$stops_obj[0]->find_station();
	else 
	  $station[0]=$stops_obj[0];
      }
      if($stops[1]) {
	if(method_exists($stops_obj[1], "find_station"))
	  $station[1]=$stops_obj[1]->find_station();
	else 
	  $station[1]=$stops_obj[1];
      }
//        if($stops[0]==$stops[1]) {
//	  $station=$stops;
////	  if($r=find_station($stops)) {
////	    $station[0]="rel_{$r[0]->id}";
////	    $station[1]="rel_{$r[0]->id}";
////	  }
//	}
//	else {
//	  if($stops[0]) {
//	    $station[0]="node_{$stops[0]->id}";
//	    if($r=find_station_rel($stops[0]->id))
//	      $station[0]="rel_{$r[0]->id}";
//	  }
//	  if($stops[1]) {
//	    $station[1]="node_{$stops[1]->id}";
//	    if($r=find_station_rel($stops[1]->id))
//	      $station[1]="rel_{$r[0]->id}";
//	  }
//	}

      if((!$stops[0])&&(!$stops[1])) {
      }
      elseif(!$stops[0]) {
	$text.="  <tr>\n";
	$text.="    <td class='bullet'>|</td>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[1]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='details'><a href='#{$station[1]->id}' onMouseOver='set_highlight([\"$stops[1]\"])' onMouseOut='unset_highlight()'>".$stops_obj[1]->long_name()."</a></td>\n";
	$text.="  </tr>\n";
      }
      elseif(!$stops[1]) {
	$text.="  <tr>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='bullet'>|</td>\n";
	$text.="    <td class='details'><a href='#{$station[0]->id}' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>".$stops_obj[0]->long_name()."</a></td>\n";
	$text.="  </tr>\n";
      }
      elseif($stops[0]==$stops[1]) {
	$text.="  <tr>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[1]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='details'><a href='#{$station[0]->id}' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>".$stops_obj[0]->long_name()."</a></td>\n";
	$text.="  </tr>\n";
      }
      elseif($stops_obj[0]->long_name()==$stops_obj[1]->long_name()) {
	$text.="  <tr>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[1]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='details'><a href='#{$station[0]->id}' onMouseOver='set_highlight([\"{$stops[0]}\", \"{$stops[1]}\"])' onMouseOut='unset_highlight()'>".$stops_obj[1]->long_name()."</a></td>\n";
	$text.="  </tr>\n";
      }
      else {
	$text.="  <tr>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='bullet'>|</td>\n";
	$text.="    <td class='details'><a href='#{$station[0]->id}' onMouseOver='set_highlight([\"{$stops[0]}\"])' onMouseOut='unset_highlight()'>".$stops_obj[0]->long_name()."</a></td>\n";
	$text.="  </tr>\n";
	$text.="  <tr>\n";
	$text.="    <td class='bullet'>|</td>\n";
	$text.="    <td class='bullet' onMouseOver='set_highlight([\"{$stops[1]}\"])' onMouseOut='unset_highlight()'>O</td>\n";
	$text.="    <td class='details'><a href='#{$station[1]->id}' onMouseOver='set_highlight([\"{$stops[1]}\"])' onMouseOut='unset_highlight()'>".$stops_obj[1]->long_name()."</a></td>\n";
	$text.="  </tr>\n";
      }
    }

    $text.="</tbody></table>\n";
    $ret[]=array("stops", $text);
  }

  return $ret;
}

register_hook("info", route_info);
