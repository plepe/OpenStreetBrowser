<?
$importance_levels=array("global", "international", "national", "regional", "urban", "suburban", "local");
$scales_levels=array(
  0,
  500000000,
  200000000, 
  100000000,
  50000000,
  25000000,
  12500000,
  6500000,
  3000000,
  1500000,
  750000,
  400000,
  200000,
  100000,
  50000,
  25000,
  12500,
  5000,
  2500,
  1000);
$scale_icon=array("global"=>2, "international"=>5, "national"=>8, "regional"=>11, "urban"=>13, "suburban"=>15, "local"=>17);
$scale_text=array("global"=>4, "international"=>8, "national"=>10, "regional"=>13, "urban"=>15, "suburban"=>16, "local"=>18);
include_once "categories_sql.php";
$default_style=array(
  "point|icon_style"=>"allow_overlap: true;",
  "point|icon_label_style"=>"fontset_name: book; fill: #000000; size: 8; allow_overlap: true; halo_fill: #ffffff; halo_radius: 1;",
  "line_text_style"=>"fontset_name: book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1; spacing: 300;",
  "point|icon_text_style"=>"fontset_name: book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1",
  "line|icon_label_style"=>"fontset_name: book; fill: #000000; size: 8; allow_overlap: false; min_distance: 15; spacing: 200; placement: line; halo_fill: #ffffff; halo_radius: 1;",
  "line|icon_text_style"=>"fontset_name: book; fill: #000000; size: 8; allow_overlap: false; min_distance: 15; placement: point; halo_fill: #ffffff; halo_radius: 1;",
  "line_text_style"=>"fontset_name: book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1; spacing: 300;",
  "line_style"=>"stroke-width: 2; stroke: #7f7f7f;",
  "polygon_style"=>"fill-opacity: 0.5; fill: #7f7f7f;",
);

$categories_fontsets=array(
  "book"=>array("DejaVu Sans Book", "unifont Medium"),
  "bold"=>array("DejaVu Sans Bold", "unifont Medium"),
  "oblique"=>array("DejaVu Sans Oblique", "unifont Medium"),
);

function category_version() {
  global $lists_dir;

  chdir($lists_dir);
  $p=popen("git log -n1", "r");
  $r=fgets($p);
  pclose($p);

  if(!preg_match("/^commit ([a-z0-9]+)/", $r, $m)) {
    print "Couldn't get file version.\n";
    exit;
  }
  return $m[1];
}

function postprocess() {
  global $req;
  global $columns;
  global $columns_all;

  $res=array();
  foreach($req as $category=>$d1) {
    foreach($d1 as $importance=>$d2) {
      foreach($d2 as $tables=>$d4) {
	$d3=$d4['case'];
	$d3_sort=array_keys($d3);
	sort($d3_sort);
	$ret="";
	foreach($d3_sort as $p) {
	  $sqlstr=$d3[$p];
	  $ret.=implode("\n", $sqlstr);
	}
	$res[$category][$importance][$tables]['case']=$ret;
      }
    }

    if($columns[$category]) {
      $cols=array_keys($columns[$category]);
      $ret1=array();
      foreach($columns[$category] as $importance=>$d2) {
	foreach($d2 as $tables=>$d3) {
	  foreach($d3 as $col=>$vals) {
	    $res[$category][$importance][$tables]['columns'][$col]=$vals;
	    // if all values are "positive" (no 'not null' and no 'not in (...)') 
	    // then we can make use of indices
	    $pos=true;
	    foreach($vals as $v) {
	      if((substr($v, 0, 1)=="!")||($v=="*")) {
		$pos=false;
	      }
	    }

	    if($pos)
	      $res[$category][$importance][$tables]['where'][]="\"$col\" in ('".implode("', '", $vals)."')";
	  }
	}
      }
    }

    if($columns_all[$category]) {
      $cols=array_keys($columns_all[$category]);
      $ret1=array();
      foreach($columns_all[$category] as $tables=>$d2) {
	foreach($list_importance as $importance) {
	  $res[$category][$importance][$tables]['where_imp']=array();
	  foreach($d2 as $col=>$vals) {
	    $res[$category][$importance][$tables]['columns'][$col]=$vals;
	    $res[$category][$importance][$tables]['where_imp'][]="\"$col\" in ('".implode("', '", $vals)."')";
	  }
	}
      }
    }
  }

  return $res;
}

function get_icon($file) {
  global $icon_dir;

  $icon_obj=$icon_dir->get_obj($file);

  if($icon_obj)
    return $icon_obj;

  return false;
}

// the mapnik_style_* functions return
// array(
//   'rule'=>DOM                           // DOM of the rule
//   'columns'=>"function|param1|param2" or
//   'columns'=>array("function|param1|param2", ...) 
//                                         // list of pgsql-functions to be
//                                         // called, parameters for those 
//                                         // functions are:
//                                         // osb_function(
//                                         //   osm_id as text,
//                                         //   osm_tags as hstore,
//                                         //   osm_way as geometry, 
//                                         //   rule_tags as hstore,
//                                         //   param1 as text
//                                         //   param2 as text
//                                         // ) returns text as param1;
function mapnik_style_point_icon($dom, $rule_id, $tags, $global_tags, $importance) {
  global $scales_levels;
  global $scale_icon;
  global $lists_dir;
  global $default_style;
  $add_columns=array();

  $icon=$tags->get("icon");
  if(!$icon)
    return null;

  $icon=get_icon($icon);
  if(!$icon)
    return null;

  $icon=$icon->icon_file();
  if(!$icon)
    return null;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_icon[$importance]]));

  $sym=$dom->createElement("PointSymbolizer");
  $rule->appendChild($sym);
  $sym->setAttribute("file", "$icon");
  $sym->setAttribute("type", "png");

  $size=getimagesize("$icon");

  $sym->setAttribute("width", $size[0]);
  $sym->setAttribute("height", $size[1]);

  $style=new css($default_style['point|icon_style']);
  $style->apply($global_tags->get("icon_style"));
  $style->apply($tags->get("icon_style"));
  foreach(array("file", "width", "height", "type") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym, $dom);

  if($tags->get("icon_label")) {
    $sym=$dom->createElement("TextSymbolizer");
    $rule->appendChild($sym);

    $sym->setAttribute("name", "icon_label");
    $sym->setAttribute("placement", "point");

    $style=new css($default_style['point|icon_label_style']);
    $style->apply($global_tags->get("icon_label_style"));
    $style->apply($tags->get("icon_label_style"));
    foreach(array("vertical_alignment", "name", "dy") as $a)
      unset($style->style[$a]);
    $style->dom_set_attributes($sym, $dom);
    $add_columns[]="tags_parse|icon_label";
  }

  return array('rule'=>$rule, 'columns'=>$add_columns);
}

function mapnik_style_point_text($dom, $rule_id, $tags, $global_tags, $importance) {
  global $scales_levels;
  global $scale_text;
  global $lists_dir;
  global $default_style;
  $add_columns=array();

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_text[$importance]]));

  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  $icon=$tags->get("icon");
  if($icon)
    if($icon=get_icon($icon))
      if($icon=$icon->icon_file()) {

	$size=getimagesize("$icon");
	$sym->setAttribute("dy", $size[1]/2+1);
	$sym->setAttribute("vertical_alignment", "bottom");
      }

  $add_columns[]="tags_parse|icon_text";
  $sym->setAttribute("name", "icon_text");
  $sym->setAttribute("placement", "point");

  $style=new css($default_style['point|icon_text_style']);
  $style->apply($global_tags->get("icon_text_style"));
  $style->apply($tags->get("icon_text_style"));
  print_r($style);
  foreach(array("vertical_alignment", "name", "dy") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym, $dom);

  if($tags->get("icon_text_data")) {
    $sym=$dom->createElement("TextSymbolizer");
    $rule->appendChild($sym);

    if($icon) {
      $sym->setAttribute("dy", $size[1]/2+1+$style->style['size']);
      $sym->setAttribute("vertical_alignment", "bottom");
    }

    $add_columns[]="tags_parse|icon_text_data";
    $sym->setAttribute("name", "icon_text_data");
    $sym->setAttribute("placement", "point");

    $style=new css($default_style['point|icon_text_style']);
    $style->apply($global_tags->get("icon_text_style"));
    $style->apply($tags->get("icon_text_style"));
    $style->style['size']-=2;
    foreach(array("vertical_alignment", "name", "dy") as $a)
      unset($style->style[$a]);
    $style->dom_set_attributes($sym, $dom);
  }

  return array('rule'=>$rule, 'columns'=>$add_columns);
}

function mapnik_style_polygon_polygon($dom, $rule_id, $tags, $global_tags, $importance) {
  global $scales_levels;
  global $scale_icon;
  global $lists_dir;
  global $default_style;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_icon[$importance]]));

  $sym=$dom->createElement("PolygonSymbolizer");
  $rule->appendChild($sym);

  $style=new css($default_style['polygon_style']);

  $style->apply($global_tags->get("polygon_style"));
  $style->apply($tags->get("polygon_style"));

  $style->dom_set_css_parameters($sym, $dom);

  return array('rule'=>$rule);
}

function mapnik_style_line_line($dom, $rule_id, $tags, $global_tags, $importance) {
  global $scales_levels;
  global $scale_icon;
  global $lists_dir;
  global $default_style;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_icon[$importance]]));

  $sym=$dom->createElement("LineSymbolizer");
  $rule->appendChild($sym);

  $style=new css($default_style['line_style']);

  $style->apply($global_tags->get("line_style"));
  $style->apply($tags->get("line_style"));

  $style->dom_set_css_parameters($sym, $dom);

  return array('rule'=>$rule);
}

function mapnik_style_line_icon($dom, $rule_id, $tags, $global_tags, $importance) {
  global $scales_levels;
  global $scale_icon;
  global $lists_dir;
  global $default_style;
  $add_columns=array();

  $icon=$tags->get("icon");
  if(!$icon)
    return null;

  $icon=get_icon($icon);
  if(!$icon)
    return null;

  $icon=$icon->icon_file();
  if(!$icon)
    return null;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_icon[$importance]]));

  $sym=$dom->createElement("ShieldSymbolizer");
  $rule->appendChild($sym);
  $sym->setAttribute("file", "$icon");
  $sym->setAttribute("type", "png");

  $size=getimagesize("$icon");

  $sym->setAttribute("width", $size[0]);
  $sym->setAttribute("height", $size[1]);

  if($tags->get("icon_label")) {
    $style=new css($default_style['line|icon_label_style']);

    $add_columns[]="tags_parse|icon_label";
    $sym->setAttribute("name", "icon_label");

    $style->apply($global_tags->get("icon_label_style"));
    $style->apply($tags->get("icon_label_style"));
  }
  elseif($tags->get("icon_text")) {
    $style=new css($default_style['line|icon_text_style']);

    $add_columns[]="tags_parse|icon_text";
    $sym->setAttribute("name", "icon_text");
    $sym->setAttribute("dy", $size[1]/2+4);
    $sym->setAttribute("vertical_alignment", "bottom");

    $style->apply($global_tags->get("icon_text_style"));
    $style->apply($tags->get("icon_text_style"));
  }
  else {
    $style=new css($default_style['line|icon_label_style']);

    $add_columns[]="empty_string|icon_label";
    $sym->setAttribute("name", "icon_label");
    $sym->setAttribute("no_text", "true");
  }

  foreach(array("file", "width", "height", "type") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym, $dom);

  return array('rule'=>$rule, "columns"=>$add_columns);
}

function mapnik_style_line_text($dom, $rule_id, $tags, $global_tags, $importance) {
  global $scales_levels;
  global $scale_text;
  global $lists_dir;
  global $default_style;
  $add_columns=array();

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_text[$importance]]));

  $add_columns[]="tags_parse|line_text";
  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  $sym->setAttribute("name", "line_text");
  $sym->setAttribute("placement", "line");

  $style=new css($default_style['line_text_style']);
  $style->apply($global_tags->get("line_text_style"));
  $style->apply($tags->get("line_text_style"));
  foreach(array("name") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym, $dom);

/*  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  $sym->setAttribute("name", "line_type");
  $sym->setAttribute("placement", "line");

  $style=new css("fontset_name: book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1; spacing: 300;");
  $style->apply($global_tags->get("display_style"));
  $style->apply($tags->get("display_style"));
  $style->style['size']-=2;
  foreach(array("name") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym, $dom); */

  return array('rule'=>$rule, 'columns'=>$add_columns);
}

function mapnik_get_layer($dom, $name, $sql, $shape_type="") {
  global $db;

  $layer=$dom->createElement("Layer");
  $layer->setAttribute("name", "$name");
  $layer->setAttribute("srs", "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs +over");
  $layer->setAttribute("status", "on");
  $style_name=$dom->createElement("StyleName");
  $style_name->appendChild($dom->createTextNode("$name"));
  $layer->appendChild($style_name);
  $datasource=$dom->createElement("Datasource");
  $layer->appendChild($datasource);
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "type");
  $parameter->appendChild($dom->createTextNode("postgis"));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "dbname");
  $parameter->appendChild($dom->createTextNode($db['name']));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "host");
  $parameter->appendChild($dom->createTextNode($db['host']));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "user");
  $parameter->appendChild($dom->createTextNode($db['user']));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "password");
  $parameter->appendChild($dom->createTextNode($db['passwd']));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "table");
  $parameter->appendChild($dom->createTextNode($sql));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "extent");
  $parameter->appendChild($dom->createTextNode("-20037508,-19929239,20037508,19929239"));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "geometry_field");
  if($shape_type!="") 
    $parameter->appendChild($dom->createTextNode("geo_{$shape_type}"));
  else
    $parameter->appendChild($dom->createTextNode("geo"));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "srid");
  $parameter->appendChild($dom->createTextNode("900913"));

  return $layer;
}

function categories_insert_fontsets($map, $dom) {
  global $categories_fontsets;

  foreach($categories_fontsets as $name=>$fonts) {
    // insert fontset clauses
    $fontset=$dom->createElement("FontSet");
    $fontset=$map->appendChild($fontset);
    $fontset->setAttribute("name", $name);

    // insert list of possible face names to each fontset
    foreach($fonts as $font) {
      $f=dom_create_append($fontset, "Font", $dom);
      $f->setAttribute("face_name", $font);

      $font_replace[$font]=$name;
    }
  }
}

function build_mapnik_style($id, $data, $global_tags) {
  global $importance_levels;
  global $postgis_tables;

  $layers=array("polygon_shape"=>array("reverse"),
		"line_shape" =>array("reverse"),
 		"point_icon"=>array("reverse"),
		"point_text"=>array("normal"),
		"line_text" =>array("normal"),
                "line_icon"=>array("normal"));

  $dom=new DOMDocument();
  $map=$dom->createElement("Map");
  $map->setAttribute("srs", "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs +over");
  $dom->appendChild($map);

  categories_insert_fontsets($map, $dom);

  $ret=array();
  $columns=array();
  foreach($data as $importance=>$data1) if($importance!="_") {
    foreach($data1 as $table=>$data2) {
      $style_icon=$dom->createElement("Style");
      $style_icon->setAttribute("name", "{$id}_{$importance}_{$table}_icon");
      $style_text=$dom->createElement("Style");
      $style_text->setAttribute("name", "{$id}_{$importance}_{$table}_text");
      $style_shape=$dom->createElement("Style");
      $style_shape->setAttribute("name", "{$id}_{$importance}_{$table}_shape");
      foreach($data2['rule'] as $i=>$tags) {
	$rule_id=$data2['rule_id'][$i];

	// layer polygon_shape
	if(isset($postgis_tables[$table])&&
	   in_array("polygon_shape", $postgis_tables[$table]['layers'])) {
	  $def=mapnik_style_polygon_polygon($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_shape->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}

	// layer point_icon
	if(isset($postgis_tables[$table])&&
	   in_array("point_icon", $postgis_tables[$table]['layers'])) {
	  $def=mapnik_style_point_icon($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_icon->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}

	// layer point_text
	if(isset($postgis_tables[$table])&&
	   in_array("point_text", $postgis_tables[$table]['layers'])) {
	  $def=mapnik_style_point_text($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_text->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}

	// layer line_shape
	if(isset($postgis_tables[$table])&&
	   in_array("line_shape", $postgis_tables[$table]['layers'])) {
	  $def=mapnik_style_line_line($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_shape->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}

	// layer line_text
	if(isset($postgis_tables[$table])&&
	   in_array("line_text", $postgis_tables[$table]['layers'])) {
	  $def=mapnik_style_line_text($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_text->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}

	// layer line_icon
	if(isset($postgis_tables[$table])&&
	   in_array("line_shape", $postgis_tables[$table]['layers'])) {
	  $def=mapnik_style_line_icon($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_icon->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}
      }

      print "Columns (1): ";
      print_r($columns);
      $new_columns=array();
      foreach($columns as $col) {
	if(!isset($col));
	else if(is_string($col)) {
	  $new_columns[]=$col;
	}
	else if(is_array($col)) {
	  foreach($col as $col1) {
	    $new_columns[]=$col1;
	  }
	}
      }
      $columns=array_unique($new_columns);

      print "Columns (2): ";
      print_r($columns);

      $sql=$data2['sql'];
      $sql_select=array();
      $sql_join=array();
      $sql_select[]="t.*";

      foreach($columns as $col) {
	$el=explode("|", $col);
	$str="osb_$el[0](t.osm_id, t.osm_tags, t.geo, t.rule_tags";
	for($i=1; $i<sizeof($el); $i++)
	  $str.=", ".postgre_escape($el[$i]);
	$str.=") as \"$el[1]\"";
	$sql_select[]=$str;
      }

      $sql_select="\n  ".implode(",\n  ", $sql_select);
      $sql_join="\n  ".implode("\n  ", $sql_join);

      $sql="(select{$sql_select} from ($sql) as t{$sql_join}) as u";

      if(in_array($table, array("polygon"))) {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_shape", $sql, "polygon");
	$map_layers['polygon_shape'][$importance][]=$style_shape;
	$map_layers['polygon_shape'][$importance][]=$layer;
      }
      if(in_array($table, array("point", "polygon", "point_extract"))) {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_icon", $sql, "point");
	$map_layers['point_icon'][$importance][]=$style_icon;
	$map_layers['point_icon'][$importance][]=$layer;

	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_text", $sql, "point");
	$map_layers['point_text'][$importance][]=$style_text;
	$map_layers['point_text'][$importance][]=$layer;
      }
      else {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_shape", $sql, "line");
	$map_layers['line_shape'][$importance][]=$style_shape;
	$map_layers['line_shape'][$importance][]=$layer;

	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_text", $sql, "line");
	$map_layers['line_text'][$importance][]=$style_text;
	$map_layers['line_text'][$importance][]=$layer;

	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_icon", $sql, "line");
	$map_layers['line_icon'][$importance][]=$style_icon;
	$map_layers['line_icon'][$importance][]=$layer;
      }

    }
  }

  foreach($layers as $layer=>$layer_desc) {
    array_deep_copy($importance_levels, $importance_list);
    if($layer_desc[0]=="reverse")
      $importance_list=array_reverse($importance_list);

    for($i=0; $i<sizeof($importance_list); $i++) {
      if(isset($map_layers[$layer]))
	if(isset($map_layers[$layer][$importance_list[$i]]))
	  foreach($map_layers[$layer][$importance_list[$i]] as $el)
	    $map->appendChild($el);
    }
  }

  return $dom->saveXML();
}

function build_renderd_config($id, $data, $global_tags) {
  global $lists_dir;
  global $www_host;
  $ret="";

  $ret.="[$id]\n";
  $ret.="URI=/tiles/$id/\n";
  $ret.="XML=$lists_dir/$id.mapnik\n";
  $ret.="HOST=$www_host\n";
  $ret.="VERSION={$data['_']['version']}\n";
  $ret.="\n";

  return $ret;
}

// category_check_state
// checks whether category-database is in sane state
//
// parameters:
// 
// return:
// true        oh yeah, everything alright
// message     no, contains statement
function category_check_state() {
  global $lists_dir;

  // $lists_dir set?
  if(!$lists_dir) {
    return "Variable \$lists_dir is not set!";
  }

  // No directory to change into ...
  if(!file_exists("$lists_dir")) {
    return "$lists_dir does not exist!";
  }

  // Check if git repository is ready
  if(!file_exists("$lists_dir/.git")) {
    chdir($lists_dir);
    system("git init");
    system("git commit -m 'Init' --allow-empty");

    if(!file_exists("$lists_dir/.git")) {
      return "Could not create git repository!";
    }
  }

  return true;
}

// function category_save()
// saves a category and processes it
//
// parameters:
// $id       the id of the file, could be 'new'
// $content  the content of the file
// $param    additional params
//    branch    the branch of a conflicting merge
//    version   the version of the file last loaded
//
// return:
//   array(
//     'status'=>'status message',  // boolean true if success
//     'branch'=>'id of conflicting branch'
//     'id'=>    'id of file'
//   )
function category_save($id, $content, $param=array()) {
  global $db_central;
  global $current_user;

  // Create a sql-statement to import whole category in one transaction
  $sql="begin;";

  // Load file into $file
  $file=new DOMDocument();
  if(!($file->loadXML($content))) {
    return array("status"=>"Could not load data");
  }

  // Calculate a new version ID
  $version=uniqid();

  // read main tags
  $tags=new tags();
  $root=$file->firstChild;
  $tags->readDOM($root);

  // compile version tags
  $version_tags=new tags();
  $version_tags->set("user", $current_user->username);
  $version_tags->set("date", Date("c"));

  // and old version
  $old_version=$root->getAttribute("version");
  
  // write main tags to db
  $sql.="insert into category values (".
    postgre_escape($id).", ".
    array_to_hstore($tags->data()).", ".
    "'$version', ".
    "Array['$old_version'], ".
    array_to_hstore($version_tags->data()).
    ");";

  // process rules
  $current=$root->firstChild;
  while($current) {
    if($current->nodeName=="rule") {
      // read rule tags
      $rule_id=$current->getAttribute("id");
      $tags=new tags();
      $tags->readDOM($current);

      // write rule tags to db
      $sql.="insert into category_rule values (".
	postgre_escape($id).", ".
	postgre_escape($rule_id).", ".
	array_to_hstore($tags->data()).", ".
	"'$version');";
    }

    $current=$current->nextSibling;
  }

  // set current category version
  $sql.="delete from category_current ".
    "where category_id=".postgre_escape($id).";";

  $sql.="insert into category_current values (".
    postgre_escape($id).", ".
    "'$version', now());";

  // inform other cluster servers of new category
  if(plugins_loaded("cluster_call")) {
    $sql.="select cluster_call('category_save', ".
      postgre_escape($id).");";
  }
  else {
    categories_has_saved($id);
  }

  // we are done.
  $sql.="commit;";
  sql_query($sql, $db_central);
  return array("status"=>true, "id"=>$id, "version"=>$version);
}


// category_list()
// lists all categories
//
// parameters:
// $lang      language
//
// return:
// array('category_id'=>root tags, ...)
function category_list($lang="en") {
  $ret=array();

  // get list of current categories
  $res=sql_query("select * from category_current left join category on category_current.category_id=category.category_id and category_current.version=category.version");
  while($elem=pg_fetch_assoc($res)) {
    $tags=new tags(parse_hstore($elem['tags']));
    $ret[$elem['category_id']]=$tags;
  }

  return $ret;
}

// category_load()
// returns content of a category
//
// parameters:
// $id     the if of the category
// $param  optional parameters
//   version    a certain version of that file
//
// return:
// content as text
// array('status')  on error
function category_load($id, $param=array()) {
  // Postgre-Escape id
  $pg_id=postgre_escape($id);

  // if no version supplied, return current version
  if(isset($param['version'])) {
    $version=$param['version'];
  }
  else {
    $res=sql_query("select * from category_current where category_id=$pg_id");
    if(!$elem=pg_fetch_assoc($res)) {
      return array('status'=>"'$id': No such category");
    }
    else {
      $version=$elem['version'];
    }
  }

  // Postgre-Escape version
  $pg_version=postgre_escape($version);

  // Get root of category
  $res=sql_query("select * from category where category_id=$pg_id and version=$pg_version");
  if(!$elem=pg_fetch_assoc($res)) {
    return array('status'=>"'$id/$version': No such category/version");
  }

  // Prepare returning XML
  $dom=new DOMDocument();
  $root=$dom->createElement("category");
  $dom->appendChild($root);
  $root->setAttribute("id", $id);
  $root->setAttribute("version", $version);

  // process Tags
  $tags=new tags(parse_hstore($elem['tags']));
  $tags->writeDOM($root, $dom);

  // Now process the rules
  $res=sql_query("select * from category_rule where category_id=$pg_id and version=$pg_version");
  while($elem=pg_fetch_assoc($res)) {
    // base
    $rule=$dom->createElement("rule");
    $rule->setAttribute("id", $elem['rule_id']);
    $root->appendChild($rule);

    // tags
    $tags=new tags(parse_hstore($elem['tags']));
    $tags->writeDOM($rule, $dom);
  }

  // we are done!
  return $dom->saveXML();
}

// category_history()
// returns history of a category
//
// parameters:
// $id     the id of the category
// $param  optional parameters
//
// return:
// array('status')
function category_history($id, $param=array(), $version=null) {
  global $db_central;

  $sql_id=postgre_escape($id);
  $list=array();

  if(!$version) {
    $res=sql_query("select * from category_current where category_id=$sql_id", $db_central);
    $elem=pg_fetch_assoc($res);
    $version=$elem['version'];
  }

  $res=sql_query("select * from category where category_id=$sql_id", $db_central);
  while($elem=pg_fetch_assoc($res)) {
    $elem['parent_versions']=parse_array($elem['parent_versions']);
    $list[$elem['version']]=$elem;
  }

  $ret=array();
  $last=$version;
  while($last) {
    $elem=$list[$last];

    $ret[]=$elem;
    if(sizeof($elem['parent_versions'])) {
      $last=$elem['parent_versions'][0];
    }
    else
      $last=null;
  }

  return $ret;
}

function categories_has_saved($id) {
  print "Detect saving of $id -> compile\n";
  $cat=new category($id);
  $cat->compile();
  restart_renderd();
}

function categories_init() {
  global $default_categories;
  global $category_tiles_url;

  if(isset($default_categories))
    html_export_var(array("default_categories"=>$default_categories));
  if(isset($category_tiles_url))
    html_export_var(array("category_tiles_url"=>$category_tiles_url));
}

function categories_mcp_start() {
  if(plugins_loaded("cluster_call")) {
    cluster_call_register("category_save", "categories_has_saved");
  }
}

register_hook("init", "categories_init");
register_hook("mcp_start", "categories_mcp_start");
