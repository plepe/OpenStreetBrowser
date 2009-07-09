<?
require_once("relation.php");

register_relation_type("route", "relation_route");

class relation_route extends relation {
  var $id;
  var $data;
  var $member_list;

  function __construct($id) {
    if(!ereg("^[0-9]+$", $id))
      return;
    $this->id=$id;
  }

  function long_name() {
    if(!$this->read_data())
      return;
    $tags=$this->data[tags];

    if($tags[ref]&&($tags[ref]==$tags[name]))
      $titel="$tags[ref]";
    elseif($tags[ref]&&$tags[name])
      $titel="$tags[ref] - $tags[name]";
    elseif($tags[ref])
      $titel="$tags[ref]";
    elseif($tags[name])
      $titel="$tags[name]";
    else
      $titel="(unknown)";

    return $titel;
  }

  function info() {
    global $route_types;
    global $network_names;

    if(!$this->read_data())
      return;

    $tags=$this->data[tags];

    $ret="<h1>".$this->long_name()."</h1>\n";
    $ret.="<a href='javascript:zoom_to_feature()'>zoom</a>\n";

    $ret.="<h2>General Information</h2>\n";
    $ret.="Route Type: {$route_types[$tags[route]]}<br>\n";
    $ret.="Network Type: {$network_names[$this->data[network]]}<br>\n";

    if($tags[operator])
      $ret.="Operator: {$tags[operator]}<br>\n";
    if($tags[website])
      $ret.="Links: <a href='{$tags[website]}'>Website</a><br>\n";
    if($tags[state])
      $ret.="State: {$tags[state]}<br>\n";
    if($tags[symbol])
      $ret.="Symbol: {$tags[symbol]}<br>\n";
    if($tags[description])
      $ret.="Description: {$tags[description]}<br>\n";

    $res_i=pg_query("select * from planet_osm_rels where '$this->id'=any(rels_parts) and type='network'");
    if(pg_num_rows($res_i))
      $ret.="This route is part of the networks:\n";
    while($elem_i=pg_fetch_assoc($res_i)) {
      $ret.="<li><a href='#rel_$elem_i[id]'>$elem_i[name]</a></li>\n";
    }

    if($tags[note]) {
      $ret.="<h2>Notes</h2>\n";
      $ret.="$tags[note]<br>\n";
    }

    if($this->data[members]["n"]) {
      $ret.="<h2>Stops</h2>\n";
      $stop_list=array();
      foreach($this->data[members]["n"] as $id=>$role) {
	if(eregi("^stop_([0-9]*)$", $role, $m)) {
	  $stop_list[$m[1]]=array();
	  $stop_list[$m[1]][0]=load_node($id);
	  $stop_list[$m[1]][1]=load_node($id);
	}
	elseif(eregi("^forward_stop_([0-9]*)$", $role, $m)) {
	  if(!$stop_list[$m[1]])
	    $stop_list[$m[1]]=array();
	  $stop_list[$m[1]][0]=load_node($id);
	}
	elseif(eregi("^backward_stop_([0-9]*)$", $role, $m)) {
	  if(!$stop_list[$m[1]])
	    $stop_list[$m[1]]=array();
	  $stop_list[$m[1]][1]=load_node($id);
	}
      }

      $stop_list_sort=array_keys($stop_list);
      natsort($stop_list_sort);

      $ret.="<table>\n";
      $ret.="<tr><td>&darr;</td><td>&uarr;</td></tr>\n";
      foreach($stop_list_sort as $num) {
	$stops=$stop_list[$num];
	$station=array(0, 0);

// TODO: find_station_rel optimieren ... Durch eine Abfrage ersetzen?
        if($stops[0]==$stops[1]) {
	  $station[0]="node_{$stops[0]->id}";
	  $station[1]="node_{$stops[1]->id}";
	  if($r=find_station_rel($stops[0]->id)) {
	    $station[0]="rel_{$r[0]->id}";
	    $station[1]="rel_{$r[0]->id}";
	  }
	}
	else {
	  if($stops[0]) {
	    $station[0]="node_{$stops[0]->id}";
	    if($r=find_station_rel($stops[0]->id))
	      $station[0]="rel_{$r[0]->id}";
	  }
	  if($stops[1]) {
	    $station[1]="node_{$stops[1]->id}";
	    if($r=find_station_rel($stops[1]->id))
	      $station[1]="rel_{$r[0]->id}";
	  }
	}

	$ret.="  <tr>\n";
	if((!$stops[0])&&(!$stops[1])) {
	}
	elseif(!$stops[0]) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#{$station[1]}' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[1]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	elseif(!$stops[1]) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='details'><a href='#{$station[0]}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[0]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	elseif($stops[0]==$stops[1]) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#{$station[0]}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[0]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	elseif($stops[0]->tags("name")==$stops[1]->tags("name")) {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  if($station[0]!=$station[1])
	    $station[0]="node_{$stops[0]->id},node_{$stops[1]->id}";
	  $ret.="    <td class='details'><a href='#{$station[0]}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\", \"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[1]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
	else {
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='details'><a href='#{$station[0]}' onMouseOver='set_highlight([\"node_{$stops[0]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[0]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	  $ret.="  <tr>\n";
	  $ret.="    <td class='bullet'>|</td>\n";
	  $ret.="    <td class='bullet' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>O</td>\n";
	  $ret.="    <td class='details'><a href='#{$station[1]}' onMouseOver='set_highlight([\"node_{$stops[1]->id}\"])' onMouseOut='unset_hightlight()'>".$stops[1]->tags("name")."</a></td>\n";
	  $ret.="  </tr>\n";
	}
      }
    }
    $ret.="</table>\n";

    return $ret;
  }
}


