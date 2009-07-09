<?
function search($text) {
  global $load_xml;
  $parts=explode(",", $text);
  $ret="<a class='zoom' href='javascript:list_reload()'>".lang("info_back")."</a><br>\n".
  $ret.="<h1>".lang("head_search")."</h1>\n";

  $search=array();
  $from=array();
  for($i=0; $i<sizeof($parts)-1; $i++) {
    $part=$parts[$i];
    $part=trim($part);

    if($i==0)
      $from[]="planet_osm_place p$i join search s$i on p$i.id_place_node=s$i.id";
    else
      $from[]="join planet_osm_place p$i on p$i.guess_area&&p".($i-1).".guess_area and Within(p$i.guess_area, p".($p-1).".guess_area) join search s$i on p$i.node_id=s$i.id";

    $search[]="to_tsvector('english', s$i.loc_name)@@plainto_tsquery('english', '$part')";

    $last=$i;
  }

//    $from=implode(" ", $from);
//    $search="(".implode(" or ", $search).")";
//
//    $qry="select to_textarray(name), s$last.type as type, s$last.id as id, s$last.amenity_type, s$last.amenity_val from $from where $search group by s$last.type, s$last.id, s$last.amenity_type, s$last.amenity_val";

  $part=trim($parts[sizeof($parts)-1]);
  if(sizeof($parts)>1) {
$from[]=<<<EOT
join geo on geo.way&&p$last.guess_area join search s on s.element=geo.element and geo.osm_id=s.id
EOT;
  }
  else {
    $from[]="search s";
  }

  unset($hn);
  $search1[]="to_tsvector('english', s.loc_name)@@plainto_tsquery('english', '$part')";
//  $search[]="s.loc_name similar to '$text %'";
//  $search[]="s.loc_name similar to '% $text'";

  $words=explode(" ", $part);
  if(sizeof($words)>1) {
    $search2=array();
    if((!ereg("^[0-9]+[a-z]?$", implode(" ", array_slice($words, 0, sizeof($words)-1))))&&(ereg("^[0-9]", $words[sizeof($words)-1])))
      $search2[]="to_tsvector('english', s.loc_name)@@plainto_tsquery('english', '".implode(" ", array_slice($words, 0, sizeof($words)-1))."')";
    if((!ereg("^[0-9]+[a-z]?$", implode(" ", array_slice($words, 1, sizeof($words)-1))))&&(ereg("^[0-9]", $words[0])))
      $search2[]="to_tsvector('english', s.loc_name)@@plainto_tsquery('english', '".implode(" ", array_slice($words, 1, sizeof($words)-1))."')";
    $hn[]=$words[0];
    $hn[]=$words[sizeof($words)-1];
    if(sizeof($search2))
      $search1[]="(s.amenity_type='street' and (".implode(" or ", $search2)."))";
  }

  $search[]="(".implode(" or ", $search1).")";
  $search=implode(" and ", $search);
  $hn_sql="";
  if($hn) {
    $hn_sql="(select to_textarray(number || '|' || CASE WHEN node_id is not null THEN 'node_' || node_id ELSE 'way_' || way_id END) from housenumber where coll_id=s.id and s.amenity_type='street' and number in ('".implode("', '", $hn)."')) ";
    unset($hn_new);
    foreach($hn as $i)
      if(ereg("^[0-9]*$", $i)) {
	$hn_sql="array_cat($hn_sql, (select to_textarray('$i' || '|' || 'way_' || way_id || '_' || '$i') from housenumber_line where coll_id=s.id and s.amenity_type='street' and first<$i and $i<last and interpolation in ('all', '".($i%2==0?"even":"odd")."')))";
      }
    $hn_sql=", $hn_sql as housenumber";
  }

$isin=<<<EOT
(select to_textarray(name) from (select p.name from geo l
join planet_osm_place p on Within(l.way, p.guess_area) where l.osm_id=s.id and l.element=s.element order by (CASE WHEN p.place='continent' THEN 1 WHEN p.place='country' THEN 2 WHEN p.place='state' THEN 3 WHEN p.place='region' THEN 4 WHEN p.place='county' THEN 5 WHEN p.place='city' THEN 6 WHEN p.place='town' THEN 7 WHEN p.place='village' THEN 8 WHEN p.place='hamlet' THEN 9 WHEN p.place='suburb' THEN 10 ELSE 11 END) asc, Distance(p.label, l.way) desc) as t)
EOT;

  $qry="select s.name as name, s.element as element, s.id as id, s.amenity_type, s.amenity_val $hn_sql from ".implode(" ", $from)." where $search group by s.name, s.element, s.id, s.amenity_type, s.amenity_val";
// $ret.=$qry."\n";
  $res=sql_query($qry);

  while($elem=pg_fetch_assoc($res)) {
    $list[$elem[is_in]][]=$elem;
  }

  if($list["{}"]) {
    $x=$list["{}"];
    unset($list["{}"]);
    $list["{???}"]=$x;
  }

  foreach($list as $is_in=>$list_isin) {
    $t=parse_array($is_in);
    $ret.="<b>".implode(", ", $t)."</b>:<br>\n";
    foreach($list[$is_in] as $elem) {
      $id="$elem[element]_$elem[id]";
      if($elem[housenumber]) {
	$elem[housenumber]=parse_array($elem[housenumber]);
	if(sizeof($elem[housenumber]==1)&&($elem[housenumber][0]==""))
	  unset($elem[housenumber]);
      }

      if(sizeof($elem[housenumber])) {
	foreach($elem[housenumber] as $h) {
	  $h1=explode("|", $h);
	  $ret.="<li><a href='#$h1[1]' onMouseOver='set_highlight([\"$h1[1]\"])' onMouseOut='unset_highlight()'>$elem[name] $h1[0]</a> [house]</li>";
	  $load_xml[]=$h1[1];
	}
      }
      else {
	$ret.="<li><a href='#$id' onMouseOver='set_highlight([\"$id\"])' onMouseOut='unset_highlight()'>$elem[name]</a>";
	  $load_xml[]=$id;

	if($elem[loc_name]) {
	  $elem[loc_name]=parse_array($elem[loc_name]);
	  $elem[language]=parse_array($elem[language]);
	  $locs=array_unique($elem[loc_name]);
	  if((sizeof($locs)>1)||($locs[0]!=$elem[name])) {
	    $locs_text=array();
	    foreach($locs as $l) {
	      $ret1="$l ";
	      for($i=0; $i<sizeof($elem[loc_name]); $i++) {
		if($elem[loc_name][$i]==$l)
		  $ret1.="[".$elem[language][$i]."]";
	      }
	      $locs_text[]=$ret1;
	    }
	    $ret.=" (".implode($locs_text, ", ").")";
	  }
	}
	$ret.=" [$elem[amenity_type]=$elem[amenity_val]]";
	$ret.="</li>\n";
      }
    }
  }

  return $ret;
}

function ajax_search($param, $xml) {
  global $load_xml;

  $result=$xml->createElement("result");
  $text=$xml->createTextNode(search("$param[value]"));

  $xml->appendChild($result);
  $result->appendChild($text);

  $osm=$xml->createElement("osm");
  $osm->setAttribute("generator", "PublicTransport OSM");
  $result->appendChild($osm);

  objects_to_xml($load_xml, $xml, $osm, 1, $bounds);

}
