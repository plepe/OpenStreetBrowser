<?
$routing_gpx;
$routing_end_pos;

function format_time($sec) {
  $t=array();

  if($sec>3600) {
    $t[]=sprintf("%.0fh", $sec/3600);
  }
  if(($sec<5*3600)&&($sec>60)) {
    $t[]=sprintf("%.0fm", ($sec/60)%60);
  }
  if(($sec<5*60)) {
    $t[]=sprintf("%.0fs", $sec%60);
  }

  return implode(" ", $t);
}

function routing(&$ret, $object, $param) {
  global $key_cloudmade_api;
  global $routing_gpx;
  global $routing_end_pos;
  global $ui_lang;

  if(in_array("routing", $param["info_noshow"])) {
    $ret[]=array("routing", "X");
    return;
  }

  $ret[]=array("routing", "<h4>Actions</h4>");
  if($param[pos_lon])
    $r ="<a href='javascript:set_my_pos()'>".lang("geo_change_pos")."</a><br>\n";
  else
    $r ="<button onClick='javascript:set_my_pos()'>".lang("geo_set_pos")."</button><br>\n";
  $r.="Route type: <select id='route_type' onChange='change_route_type()'>";
  foreach(array("car", "car_shortest", "bicycle", "foot") as $t) {
    $r.="<option value='$t'";
    if($t==$param[route_type])
      $r.=" selected";
    $r.=">".lang("routing_type_$t")."</option>\n";
  }
  $r.="</select><br>\n";
  $ret[]=array("routing", $r);

  if($param[pos_lon]) {
    $poss=goole_to_utm(array("lon"=>$param[pos_lon], "lat"=>$param[pos_lat]));
//    print_r($poss);
    if($param[routing_end_lon]) {
      $routing_end_pos=goole_to_utm(array("lon"=>$param[routing_end_lon], "lat"=>$param[routing_end_lat]));
    }
    else {
      $routing_end_pos=goole_to_utm($object->place()->get_centre());
//      $res=sql_query("select astext(ST_Centroid(way)) as pos from geo where element='$object->element' and osm_id='$object->only_id'");
//      $elem=pg_fetch_assoc($res);
//      if(ereg("POINT\(([0-9\.]*) ([0-9\.]*)\)", $elem["pos"], $m)) {
//	$routing_end_pos=goole_to_utm(array("lon"=>$m[1], "lat"=>$m[2]));
//      }
    }

    if(!$routing_end_pos) {
      $ret[]=array("routing", "Error: Could not determine destination");
      return;
    }

    $ret[]=array("routing", "<h4>Route</h4>\n");

    $url="http://routes.cloudmade.com/$key_cloudmade_api/api/0.3/".
         "$poss[lat],$poss[lon],$routing_end_pos[lat],$routing_end_pos[lon]/".
	 implode("/", explode("_", $param[route_type])).".gpx?lang=$ui_lang";

    if(!(@$r=file_get_contents($url))) {
      $ret[]=array("routing", "Error: Could not download route");
      unset($routing_gpx);
      return;
    }

    //$ret[]=array("routing", "$url<br>\n");
    if(substr($r, 0, 1)=="<") {
      $routing_gpx=$r;
      $routing_gpx=new DOMDocument();
      $routing_gpx->loadXML($r);
      $text="";

      $gpx=$routing_gpx->getElementsByTagname("gpx");
      if($gpx) {
	$c=$gpx->item(0)->getElementsByTagname("extensions");
	if($c) {
	  $c=$c->item(0);
	  $c1=$c->getElementsByTagname("distance");
	  $text.=lang("routing_distance").": ".
	         $c1->item(0)->textContent."m<br>\n";
	  $c1=$c->getElementsByTagname("time");
	  $text.=lang("routing_time").": ".
	         format_time($c1->item(0)->textContent)."<br/>\n";
	  $text.="<hr/>\n";
	}

	$c=$gpx->item(0)->getElementsByTagname("rtept");
	for($i=0; $i<$c->length; $i++) {
	  $c1=$c->item($i)->getElementsByTagname("desc");
	  $text.="".($c1->item(0)->textContent)."<br/>\n";
	}

	$text.="<hr/>\n";
	$text.=lang("routing_disclaimer")."<br/>\n";
      }
//      $c=$c->firstChild;
//      while($c) {
//        $r.="$c->textContent<br>\n";
//	$c=$c->nextSibling;
//      }
      $ret[]=array("routing", $text);
    }
    else {
      $ret[]=array("routing", "Error: $r");
      unset($routing_gpx);
      return;
    }
  }
}

function routing_xml($xml) {
  global $routing_gpx;
  global $routing_end_pos;

  $result=$xml->getElementsByTagname("result");
  $result=$result->item(0);

  $routing=$xml->createElement("routing");
  $result->appendChild($routing);

  if($routing_end_pos) {
    $end_pos=$xml->createElement("end_pos");
    $end_pos->setAttribute("lon", $routing_end_pos[lon]);
    $end_pos->setAttribute("lat", $routing_end_pos[lat]);
    $routing->appendChild($end_pos);
  }

  if(!$routing_gpx)
    return;

//  print $routing_gpx;
//  $dom_sxe=dom_import_simplexml($dom);
  $dom_sxe=$xml->importNode($routing_gpx->documentElement, true);

  $routing->appendChild($dom_sxe);
}

register_hook("info", routing);
register_hook("xml_done", routing_xml);

