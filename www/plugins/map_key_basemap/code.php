<?
class map_key_basemap extends map_key_cascadenik {
  function __construct() {
    parent::__construct("basemap");
  }

  function show_info($bounds) {
    global $class_info;

    load_classes("roads", $bounds);
    load_classes("road_features", $bounds);
    load_classes("land", $bounds);
    load_classes("boundaries", $bounds);
    load_classes("water", $bounds);
    load_classes("places", $bounds);
    load_classes("buildings", $bounds);
    load_classes("amenities", $bounds);
    load_classes("housenumbers", $bounds);

    $ret1="";
    $ret1.=$this->show_mss(array("places_high"), 
      array("place"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap", "tags_format"=>array("prefix"=>"tag:", "override"=>array("place"=>array("no_key"=>true)))));
    $ret1.=$this->show_mss(array("places_db"), 
      array("place"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap", "tags_format"=>array("prefix"=>"tag:", "override"=>array("place"=>array("no_key"=>true)))));
    if($ret1!="") {
      $ret.="<h4>".lang("head:places")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
//    $ret.=$this->show_mss(array("roads_casing", "roads_fill", "roads_rail"), 
//      array("highway_type"=>"=rail", "railway"=>array("=tram", "=rail"), "tracks"=>"=single"), $bounds);
    $ret1.=$this->show_mss(array("roads_extcas", "roads_extract"), 
      array("highway_type"=>array("=motorway", "=major", "=minor", "=service", "=pedestrian", "=path", "=aeroway"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("roads_casing_end", "roads_casing", "roads_fill"), 
      array("highway_type"=>array("=motorway", "=major", "=minor", "=service", "=pedestrian", "=path", "=aeroway"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("square_casing", "square_fill"), 
      array("type"=>"*"), $bounds, array("name_prefix"=>"tag:highway=pedestrian", "img_base_path"=>"plugins/basemap"));
    if($ret1!="") {
      $ret.="<h4>".lang("head:roads")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
    $ret1.=$this->show_mss(array("roads_extract"), 
      array("highway_type"=>array("=railway"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
//    $ret1.=$this->show_mss(array("roads_casing_end", "roads_casing", "roads_fill"), 
//      array("highway_type"=>array("=railway"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("roads_rail"), 
      array("railway"=>"*", "tracks"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    if($ret1!="") {
      $ret.="<h4>".lang("head:rails")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
//    $ret1.=$this->show_mss(array("roads_casing", "roads_fill", "roads_rail"), 
//      array("highway_type"=>"=rail", "railway"=>array("=tram", "=rail"), "tracks"=>"=single"), $bounds);
    $ret1.=$this->show_mss(array("roads_extract"), 
      array("highway_type"=>array("=power", "=pipeline"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("roads_casing_end", "roads_casing", "roads_fill"), 
      array("highway_type"=>array("=power", "=pipeline"), "highway_sub_type"=>"*"), $bounds, array("img_base_path"=>"plugins/basemap"));
    if($ret1!="") {
      $ret.="<h4>".lang("head:goods")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
    $ret1.=$this->show_mss(array("bound_world1"), 
      array("admin_level"=>array("=2")), $bounds, array("img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("admin"), 
      array("admin_level"=>array("=2", "=3", "=4", "=5", "=6", "=8", "=10")), $bounds, array("img_base_path"=>"plugins/basemap"));
    if($ret1!="") {
      $ret.="<h4>".lang("head:borders")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
    $ret1.=$this->show_mss(array("Map"),
      array(), $bounds, array("name_prefix"=>"map_key_basemap:landuse=water", "img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("world1"),
      array(), $bounds, array("name_prefix"=>"map_key_basemap:landuse=land", "img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("landuse_extract"),
      array("landuse"=>"*", "landuse_sub_type"=>"*"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap", "name_prefix"=>"map_key_basemap:", "name_explode"=>false));
    $ret1.=$this->show_mss(array("landuse"),
      array("landuse"=>"*", "landuse_sub_type"=>"*"), $bounds, array("prefix"=>"", "geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap", "name_prefix"=>"map_key_basemap:", "name_explode"=>false));
    if($ret1!="") {
      $ret.="<h4>".lang("head:landuse")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
    $ret1.=$this->show_mss(array("buildings"), 
      array("building"=>"*"), $bounds, array("geom"=>array("poly"=>1), "img_base_path"=>"plugins/basemap", "name_prefix"=>"map_key_basemap:"));
//    $ret.=$this->show_mss(array("amenity"), 
//      array("type"=>"*", "sub_type"=>"*"), $bounds);
    if($ret1!="") {
      $ret.="<h4>".lang("head:buildings")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

    $ret1="";
    $ret1.=$this->show_mss(array("housenumbers"), 
      array(), $bounds, array("name_prefix"=>"tag:addr:housenumber", "img_base_path"=>"plugins/basemap"));
    $ret1.=$this->show_mss(array("housenumber_lines"), 
      array(), $bounds, array("name_prefix"=>"tag:addr:interpolation", "img_base_path"=>"plugins/basemap"));
    if($ret1!="") {
      $ret.="<h4>".lang("head:housenumbers")."</h4>\n";
      $ret.="<table>\n";
      $ret.=$ret1;
      $ret.="</table>\n";
    }

//    $ret.=$this->show_mss("places", "places_db", "Places", $bounds);
//    $ret.=$this->show_mss("boundaries", array("world1", "admin"), "Borders", $bounds);
//    $ret.=$this->show_mss("land", array("world1", "world", "coastpoly", "landuse"), "Landuse", $bounds);
//    $ret.=$this->show_mss("water", array("waterarea", "water"), "Water", $bounds);
//    $ret.=$this->show_mss("land", array("buildings"), "Buildings", $bounds);

    return $ret;
  }
}

function map_key_basemap_map_key($list, $entries) {
  $key=new map_key_basemap();
  $list[]=array(-10, $key);
}

register_hook("map_key", "map_key_basemap_map_key");
