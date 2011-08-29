<?
class map_key_basemap extends map_key_entry {
  function show_info($bounds) {
    if($bounds[overlays][pt]) {
      load_classes("overlay_pt", $bounds);

      $ret.="<h4>".lang("map_key_overlay_pt:pt")."</h4>\n";
      $ret.="<table>\n";

      $ret.=$this->show_mss(array("routes", "routestext"), 
	array("route"=>array("=rail", "=subway", "=tram", "=ferry", "=bus", "=tram_bus"), "network"=>"*"), $bounds);

      $ret.=$this->show_mss(array("stations_top", "stations_center"),
	array("network"=>"*"), $bounds);

      $ret.="</table>\n";
    }
  }
}

