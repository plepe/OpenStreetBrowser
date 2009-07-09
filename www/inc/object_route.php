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

    if(eregi("^stop_([0-9]*)$", $role, $m)) {
      $stop_list[$m[1]]=array();
      $stop_list[$m[1]][0]=$id;
      $stop_list[$m[1]][1]=$id;
      $load_list[]=$id;
    }
    elseif(eregi("^forward_stop_([0-9]*)$", $role, $m)) {
      if(!$stop_list[$m[1]])
	$stop_list[$m[1]]=array();
      $stop_list[$m[1]][0]=$id;
      $load_list[]=$id;
    }
    elseif(eregi("^backward_stop_([0-9]*)$", $role, $m)) {
      if(!$stop_list[$m[1]])
	$stop_list[$m[1]]=array();
      $stop_list[$m[1]][1]=$id;
      $load_list[]=$id;
    }
  }

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
