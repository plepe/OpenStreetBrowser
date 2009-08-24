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
      $stop_list[$id]=array("dir"=>0, "id"=>$id, "role"=>"both");
      $stop_id_list[]=$mem[0]->only_id;
      $load_list[]=$id;
    }
    elseif(eregi("^forward[:_]stop.*$", $role, $m)) {
      $stop_index[$id]=sizeof($stop_list);
      $stop_list[$id]=array("dir"=>1, "id"=>$id, "role"=>"forward");
      $stop_id_list[]=$mem[0]->only_id;
      $load_list[]=$id;
    }
    elseif(eregi("^backward[:_]stop.*$", $role, $m)) {
      $stop_index[$id]=sizeof($stop_list);
      $stop_list[$id]=array("dir"=>-1, "id"=>$id, "role"=>"backward");
      $stop_id_list[]=$mem[0]->only_id;
      $load_list[]=$id;
    }
  }

  load_objects($load_list);

  $res=sql_query("select 'way_'||l.osm_id as way_id, 'node_'||p.osm_id as stop_id, sequence_id as pos from planet_osm_point p join way_nodes wn on wn.node_id=p.osm_id join planet_osm_line l on wn.way_id=l.osm_id join relation_members rm on rm.member_type=2 and rm.member_id=l.osm_id where rm.relation_id='{$object->only_id}' and p.osm_id in (".implode(",", $stop_id_list).")");
  while($elem=pg_fetch_assoc($res)) {
    $stop_list[$elem[stop_id]][ways][]=array("way_id"=>$elem[way_id], "pos"=>$elem[pos]);
    $way_stop_list[$elem[way_id]][$elem[stop_id]]=$elem;
    unset($stop_id_list[array_search(substr($elem[stop_id], 5), $stop_id_list)]);
  }

  if(sizeof($stop_id_list)) {
    $res=sql_query("select 'way_'||l.osm_id as way_id, 'node_'||p.osm_id as stop_id, (select sequence_id from way_nodes wn join planet_osm_nodes nodes on wn.node_id=nodes.id where wn.way_id=l.osm_id order by Distance(p.way, geometryfromtext('POINT('||nodes.lon||' '||nodes.lat||')', 900913)) asc limit 1) as pos, Distance(p.way, l.way) as d from planet_osm_point p join planet_osm_line l on geometryfromtext('POLYGON(('||".
      "XMIN(p.way)-50||' '||YMIN(p.way)-50||','||".
      "XMAX(p.way)+50||' '||YMIN(p.way)-50||','||".
      "XMAX(p.way)+50||' '||YMAX(p.way)+50||','||".
      "XMIN(p.way)-50||' '||YMAX(p.way)+50||','||".
      "XMIN(p.way)-50||' '||YMIN(p.way)-50||'))', 900913)&&l.way and Distance(p.way, l.way)<20 join relation_members rm on l.osm_id=rm.member_id and rm.member_type=2 where rm.relation_id='{$object->only_id}' and p.osm_id in (".implode(",", $stop_id_list).")");
    while($elem=pg_fetch_assoc($res)) {
      $stop_list[$elem[stop_id]][ways][]=array("way_id"=>$elem[way_id], "pos"=>$elem[pos]);
      $way_stop_list[$elem[way_id]][$elem[stop_id]]=$elem;
    }
  }

  $res=sql_query("select 'way_'||member_id as way_id, 'node_'||(select node_id from way_nodes where way_id=member_id and sequence_id=0) as first, 'node_'||(select node_id from way_nodes where way_id=member_id order by sequence_id desc limit 1) as last from relation_members rm where relation_id='{$object->only_id}' and member_type='2'");
  while($elem=pg_fetch_assoc($res)) {
    $ways[$elem[way_id]]=$elem;
    $nodes[$elem[first]][]=$elem[way_id];
    $nodes[$elem[last]][]=$elem[way_id];
  }
  
  foreach($nodes as $node_id=>$way_ids) {
    $nodes[$node_id]=array_unique($way_ids);
  }

  $data=array(
    "stop_list"=>$stop_list,
    "stop_index"=>$stop_index,
    "way_stop_list"=>$way_stop_list,
    "ways"=>$ways,
    "nodes"=>$nodes);

  function possible_way($stop0, $cur_stop_id, &$data, $rek=array(), &$tried=array()) {
    $ret=array();
    if(!$stop0[ways])
      return array();
    
    //print_r($stop0[ways]);
    $ways=array();
    foreach($stop0[ways] as $w) {
      $r="$w[way_id]_$stop0[dir]_$stop0[id]";
      if(!in_array($r, $rek)) {
	$ways[]=$w;
	$rek[]=$r;
      }

      if($tried[$r]) {
	//print "Already tried $r\n";
	return;
      }
      $tried[$r]=1;
    }

    if(!sizeof($ways))
      return array();

//    if(in_array("$stop0[dir]$stop0[id]", $rek))
//      return array();
//    $rek[]="$stop0[dir]$stop0[id]";
//    print "rek ".sizeof($rek).": ".implode(",", $rek)."\n";
//    print "stop0 ";print_r($stop0);

    foreach($stop0[ways] as $way0) {
      $pos0=$way0[pos];
      $poss=array();
//      print "way_stop_list "; print_r($data[way_stop_list][$way0[way_id]]);
      if($data[way_stop_list][$way0[way_id]])
      foreach($data[way_stop_list][$way0[way_id]] as $poss_stop) {
//	print "poss_stop "; print_r($poss_stop);
	if($poss_stop[stop_id]!=$cur_stop_id) {
	  $stopo=$data[stop_list][$poss_stop[stop_id]];
//	print "stop0 "; print_r($stop0);
//	print "stopo "; print_r($stopo);
	  if(($stop0["dir"]==1)&&($poss_stop["pos"]>$pos0)&&($stopo["dir"]!=-1))
	    $poss[$poss_stop["pos"]]=$poss_stop;
	  if(($stop0["dir"]==-1)&&($poss_stop["pos"]<$pos0)&&($stopo["dir"]!=1))
	    $poss[$poss_stop["pos"]]=$poss_stop;
	}
      }
      //print "dir".$stop0[dir]; print "poss ";print_r($poss);
      $k=array_keys($poss);
      if($stop0["dir"]==1)
	sort($k);
      else
	rsort($k);
//	print "dir".$stop0[dir]; print_r($k);

      if(sizeof($poss)) {
	$ret[]=array("stop_id"=>$poss[$k[0]][stop_id], "dir"=>$stop0["dir"], "way"=>$way0[way_id], "rek"=>$rek);
      }
      else {
	$end=$data[ways][$way0[way_id]];
	if($stop0["dir"]==1)
	  $end=$end[last];
	else
	  $end=$end[first];
//	  print "END";
//	  print_r($end);
//	  print_r($way0);
//print_r($data[nodes][$end]);
	foreach($data[nodes][$end] as $w) {
	  if(($w==$way0[way_id])&&(sizeof($data[nodes][$end])>1)) {
	  }
	  elseif($data[ways][$w][first]==$end) {
	    $s=array(
	      "dir"=>1,
	      "id"=>$end,
	      "pos"=>0,
	      "ways"=>array(array("way_id"=>$w, "pos"=>0)));
	    if($d=possible_way($s, $cur_stop_id, $data, $rek, $tried))
	      $ret=array_merge($ret, $d);
	  }
	  else {
	    $s=array(
	      "dir"=>-1,
	      "id"=>$end,
	      "pos"=>9999,
	      "ways"=>array(array("way_id"=>$w, "pos"=>9999)));
	    if($d=possible_way($s, $cur_stop_id, $data, $rek, $tried))
	      $ret=array_merge($ret, $d);
	  }
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

  $end_node=array(null, null);
  //print_r($data);

  foreach($stop_list as $i=>$stop) {
    //print "$i\n";
    if($stop[ways]) foreach($stop[ways] as $way) {
      $s=$stop;
      $s[ways]=array($way);

      if($stop["dir"]>-1) {
	$s["dir"]=1;
	$poss=possible_way($s, $stop[id], $data);
	$stop_list[$i][possible][$way[way_id]][1]=$poss;
	foreach($poss as $p) {
	  $stop_list[$p[stop_id]][come][$p[way]][$p[dir]][]=array("stop_id"=>$stop[id], "dir"=>$stop["dir"], "way"=>$way[way_id]);
	}
      }
      if($stop["dir"]<1) {
	$s["dir"]=-1;
	$poss=possible_way($s, $stop[id], $data);
	$stop_list[$i][possible][$way[way_id]][-1]=$poss;
	foreach($poss as $p) {
	  $stop_list[$p[stop_id]][come][$p[way]][$p[dir]][]=array("stop_id"=>$stop[id], "dir"=>$stop["dir"], "way"=>$way[way_id]);
	}
      }
    }
    //print_r($stop_list[$i]);
  }

  function posscome_merge($posscome) {
    $ret=array();
    if(!$posscome)
      return array();
    foreach($posscome as $way) {
     foreach($way as $dir) {
       foreach($dir as $poss) {
	 $ret[]=$poss;
       }
     }
    }

    return $ret;
  }

  $turn=1;
  $last=array("possible"=>null, "come"=>null);
  foreach($stop_list as $i=>$stop) {
    $stop_ob=load_object($stop[id]);
    //print $stop[id]." ".$stop_ob->tags->get("name")."\n";

    $success_p=0;
    if($last["possible"]) {
      $come_match=array();
      foreach($last["possible"] as $p) {
	if($p[stop_id]==$stop[id]) {
	  $success_p=1;
	  foreach($stop["come"] as $come_way_id=>$come_way) {
	    foreach($come_way as $come_dir=>$come_list) {
	      foreach($come_list as $come) {
		if($come[stop_id]==$last["possible_id"]) {
		  $come_match[]=array("way_id"=>$come_way_id, "dir"=>$come_dir);
		}
	      }
	    }
	  }
	}
      }

      if($success_p) {
	$stop_list[$last["possible_id"]]["next0"]=$stop[id];
	$stop_list[$stop[id]]["prev0"]=$last["possible_id"];
	$last["possible_id"]=$stop[id];
	//print "$success_p $dir_1 $way_1 $dir1 $way1\n";
	$stop_list[$stop[id]][come_match]=$come_match;
//	print "come_match ";print_r($come_match);

	$last["possible"]=array();
	foreach($come_match as $match) {
	  if($stop["possible"][$match["way_id"]]&&
	     $stop["possible"][$match["way_id"]][$match["dir"]])
	    $last["possible"]=array_merge($last["possible"],
	      $stop["possible"][$match["way_id"]][$match["dir"]]);
	}
      }
    }
    else {
      $last["possible"]=posscome_merge($stop[possible]);
      $last["possible_id"]=$stop[id];
    }

    $success_n=0;
    if($last["come"]) {
      $poss_match=array();
      foreach($last["come"] as $p) {
	if($p[stop_id]==$stop[id]) {
	  $success_n=1;
	  foreach($stop["possible"] as $poss_way_id=>$poss_way) {
	    foreach($poss_way as $poss_dir=>$poss_list) {
	      foreach($poss_list as $poss) {
		if($poss[stop_id]==$last["come_id"]) {
		  $poss_match[]=array("way_id"=>$poss_way_id, "dir"=>$poss_dir);
		}
	      }
	    }
	  }
	}
      }

      if($success_n) {
	$stop_list[$last["come_id"]]["next1"]=$stop[id];
	$stop_list[$stop[id]]["prev1"]=$last["come_id"];
	$last["come_id"]=$stop[id];
	$stop_list[$stop[id]][poss_match]=$poss_match;
	//print "poss_match"; print_r($poss_match);

      //print "stop ";print_r($stop);
	$last["come"]=array();
	foreach($poss_match as $match) {
	  if($stop["come"][$match["way_id"]]&&
	     $stop["come"][$match["way_id"]][$match["dir"]])
	    $last["come"]=array_merge($last["come"],
	      $stop["come"][$match["way_id"]][$match["dir"]]);
	}
      }
    }
    else {
      $last["come"]=posscome_merge($stop[come]);
      $last["come_id"]=$stop[id];
    }

    if((!$success_p)&&(!$success_n)) {
      $last["possible"]=posscome_merge($stop[possible]);
      $last["possible_id"]=$stop[id];
      $last["come"]=posscome_merge($stop[come]);
      $last["come_id"]=$stop[id];
    }
//    if(sizeof($result)&&
//       ($result[sizeof($result)-1][name]==$stop_ob->tags->get("name"))) {
//    }
//    else {
//      $res=array(
//        "name"=>$stop_ob->tags->get("name"),
//        "stop"=>$stop);
      //print "last "; print_r($last);
    $stop_list[$i][last]=$last;
  }

//  print_r($result);

//	print "success {$s[id]}->$stop[id]: $dir\n";
//	$stop_list[$s[id]]["dir_$s[dir]"]=array("dir"=>$dir, "id"=>$stop[id]);
//	unset($no_next[$sp]);
//      }
//  }
  //print_r($stop_list);
//  print_r($way_stop_list);
//  print_r($ways);

  if(sizeof($stop_list)) {
    $text.="<table cellpadding=0 cellspacing=0>\n";

    $waiting=array(0, 0);
    foreach($stop_list as $stop) {
      $stop_ob=load_object($stop[id]);
      if($stop[role]=="both") {
	$waiting=array(0, 0);
	if($stop[prev0]&&$stop[next0]) {
	  $img_left="stop_left_both";
	  $waiting[0]=1;
	}
        elseif($stop[prev0]) {
	  $img_left="stop_left_prev";
	}
	elseif($stop[next0]) {
	  $img_left="stop_left_next";
	  $waiting[0]=1;
	}
	else {
	  $img_left="stop_left_none";
	}
	if($stop[prev1]&&$stop[next1]) {
	  $img_right="stop_right_both";
	  $waiting[1]=1;
	}
        elseif($stop[prev1]) {
	  $img_right="stop_right_prev";
	}
	elseif($stop[next1]) {
	  $img_right="stop_right_next";
	  $waiting[1]=1;
	}
	else {
	  $img_right="stop_right_none";
	}

        $highlight="onMouseOver='set_highlight([\"$stop_ob->id\"])' onMouseOut='unset_highlight()'";
	$text.="<tr><td $highlight><img src='img/$img_left.png'></td><td $highlight><img src='img/$img_right.png'></td><td>{$stop_ob->tags->get("name")}</td></tr>\n";
      }
      else {
	// right or left?
	if($stop[prev1]||$stop[next1])
	  $side=1;
	else
	  $side=0;

	$waiting[$side]=0;
	if($stop["prev$side"]&&$stop["next$side"]) {
	  $img="stop_single_both";
	  $waiting[$side]=1;
	}
        elseif($stop["prev$side"])
	  $img="stop_single_prev";
	elseif($stop["next$side"]) {
	  $img="stop_single_next";
	  $waiting[$side]=1;
	}
	else {
	  $img="stop_single_none";
	}

        $otherside=(int)!$side;
	if((!$waiting[$otherside])&&($stop["next$otherside"])) {
	  $img_other="stop_to".($side?"right":"left")."_next";
	  $img.="_from".($side?"left":"right");
	  $waiting[$otherside]=1;
	}
	elseif(($waiting[$otherside])&&($stop["prev$otherside"])&&(!$stop["next$otherside"])) {
	  $img_other="stop_to".($side?"right":"left")."_prev";
	  $img.="_from".($side?"left":"right");
	  $waiting[$otherside]=0;
	}
	elseif($waiting[$otherside]) {
	  $img_other="stop_none_both";
	}
	else {
	  $img_other="stop_none_none";
	}

        $text.="<tr>";
        $highlight="onMouseOver='set_highlight([\"$stop_ob->id\"])' onMouseOut='unset_highlight()'";
	if($side)
	  $text.="<td><img src='img/$img_other.png'></td><td $highlight><img src='img/$img.png'></td>";
	else
	  $text.="<td $highlight><img src='img/$img.png'></td><td><img src='img/$img_other.png'></td>";
	
	$text.="<td>{$stop_ob->tags->get("name")}</td></tr>\n";
      }
    }

    $text.="</table>\n";

    $text.="<pre>".print_r($stop_list, 1)."</pre>";
    $ret[]=array("stops", $text);
  }

  return $ret;

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
