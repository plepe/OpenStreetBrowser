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
include "categories_sql.php";
$default_style=array(
  "point|icon_style"=>"allow_overlap: true;",
  "point|icon_label_style"=>"face_name: DejaVu Sans Book; fill: #000000; size: 8; allow_overlap: true;",
  "line_text_style"=>"face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1; spacing: 300;",
  "point|icon_text_style"=>"face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1",
  "line|icon_label_style"=>"face_name: DejaVu Sans Book; fill: #000000; size: 8; allow_overlap: false; min_distance: 15; spacing: 200; unlock_image: true;",
  "line_text_style"=>"face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1; spacing: 300;",
  "line_style"=>"stroke-width: 2; stroke: #7f7f7f;",
  "polygon_style"=>"fill-opacity: 0.5; fill: #7f7f7f;",
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
	$sym->setAttribute("dy", $size[1]);
	$sym->setAttribute("vertical_alignment", "middle");
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
      $sym->setAttribute("dy", $size[1]+$style->style['size']);
      $sym->setAttribute("vertical_alignment", "middle");
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

  if($tags->get("icon_label")) {
    $add_columns[]="tags_parse|icon_label";
    $sym=$dom->createElement("ShieldSymbolizer");
    $rule->appendChild($sym);
    $sym->setAttribute("file", "$icon");
    $sym->setAttribute("type", "png");

    $size=getimagesize("$icon");

    $sym->setAttribute("width", $size[0]);
    $sym->setAttribute("height", $size[1]);

    $style=new css($default_style['line|icon_label_style']);
    $sym->setAttribute("name", "icon_label");

    $style->apply($global_tags->get("icon_label_style"));
    $style->apply($tags->get("icon_label_style"));
    foreach(array("file", "width", "height", "type") as $a)
      unset($style->style[$a]);
    $style->dom_set_attributes($sym, $dom);
  }

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

  $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ffffff; halo_radius: 1; spacing: 300;");
  $style->apply($global_tags->get("display_style"));
  $style->apply($tags->get("display_style"));
  $style->style['size']-=2;
  foreach(array("name") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym, $dom); */

  return array('rule'=>$rule, 'columns'=>$add_columns);
}

function mapnik_get_layer($dom, $name, $sql) {
  global $db_name;

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
  $parameter->appendChild($dom->createTextNode("$db_name"));
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
  $parameter->appendChild($dom->createTextNode("geo"));
  $parameter=$dom->createElement("Parameter");
  $datasource->appendChild($parameter);
  $parameter->setAttribute("name", "srid");
  $parameter->appendChild($dom->createTextNode("900913"));

  return $layer;
}

function build_mapnik_style($id, $data, $global_tags) {
  global $importance_levels;

  $layers=array("polygon_shape"=>array("reverse"),
		"line_shape" =>array("reverse"),
 		"point_icon"=>array("reverse"),
		"point_text"=>array("normal"),
		"line_text" =>array("normal"),
                "line_icon"=>array("normal"));

  sql_query("delete from categories_def where category_id='$id'");

  $dom=new DOMDocument();
  $map=$dom->createElement("Map");
  $map->setAttribute("srs", "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs +over");
  $dom->appendChild($map);
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
	if(in_array($table, array("polygon"))) {
	  $def=mapnik_style_polygon_polygon($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_shape->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}
	if(in_array($table, array("point", "polygon"))) {
	  $def=mapnik_style_point_icon($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_icon->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	  $def=mapnik_style_point_text($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_text->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}
	elseif(in_array($table, array("line"))) {
	  $def=mapnik_style_line_line($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_shape->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	  $def=mapnik_style_line_text($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_text->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	  $def=mapnik_style_line_icon($dom, $rule_id, $tags, $global_tags, $importance);
	  if(isset($def)) {
	    $style_icon->appendChild($def['rule']);
	    $columns[]=$def['columns'];
	  }
	}

        sql_query("insert into categories_def values (".
		  postgre_escape($rule_id).", ".postgre_escape($id).", ".
		  array_to_hstore($tags->data()).");");
      }

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
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_shape", $sql);
	$map_layers['polygon_shape'][$importance][]=$style_shape;
	$map_layers['polygon_shape'][$importance][]=$layer;
      }
      if(in_array($table, array("point", "polygon"))) {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_icon", $sql);
	$map_layers['point_icon'][$importance][]=$style_icon;
	$map_layers['point_icon'][$importance][]=$layer;

	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_text", $sql);
	$map_layers['point_text'][$importance][]=$style_text;
	$map_layers['point_text'][$importance][]=$layer;
      }
      else {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_shape", $sql);
	$map_layers['line_shape'][$importance][]=$style_shape;
	$map_layers['line_shape'][$importance][]=$layer;

	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_text", $sql);
	$map_layers['line_text'][$importance][]=$style_text;
	$map_layers['line_text'][$importance][]=$layer;

	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_icon", $sql);
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
      if($map_layers[$layer])
	if($map_layers[$layer][$importance_list[$i]])
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
  $ret.="XML=$lists_dir/$id.xml.mapnik\n";
  $ret.="HOST=$www_host\n";
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
  global $lists_dir;
  global $current_user;

  if(($state=category_check_state())!==true) {
    return array('status'=>$state);
  }

  if($id=="new") {
    $id=uniqid("cat_");
  }
  if(!$id) {
    return array("status"=>"No ID given");
    exit;
  }

  if(!file_exists("$lists_dir")) {
    mkdir("$lists_dir");
    if(!file_exists("$lists_dir")) {
      print "$lists_dir doesn't exist! Create and make sure it's writable by the webserver!";
      exit;
    }
  }

  lock_dir("$lists_dir");

  $file=new DOMDocument();
  if(!($file->loadXML($content))) {
    unlock_dir($lists_dir);
    return array("status"=>"Could not load data");
  }

  $l=$file->getElementsByTagName("category");
  for($i=0; $i<$l->length; $i++) {
    $version=$l->item($i)->getAttribute("version");
    $l->item($i)->removeAttribute("version");
  }

  chdir($lists_dir);
  if($branch=$param[branch]) {
    system("git checkout $branch");
    system("git rebase $param[version]");

    $f=fopen("$lists_dir/$id.xml", "w");
    fprintf($f, $file->saveXML());
    fclose($f);

    system("git add $id.xml");
    $author=$current_user->get_author();
    system("git commit -m 'Fix merge' --author='$author'");
  }
  else {
    $branch=uniqid();

    system("git branch $branch $version");
    system("git checkout $branch");

    $f=fopen("$lists_dir/$id.xml", "w");
    fprintf($f, $file->saveXML());
    fclose($f);

    system("git add $id.xml");
    $author=$current_user->get_author();
    system("git commit -m 'Change category $id' --author='$author'");
  }

  $p=popen("git rebase master", "r");
  $error=0;
  while($r=fgets($p)) {
    if(preg_match("/^CONFLICT /", $r)) {
      $error=1;
    }
  }
  pclose($p);

  if($error) {
    system("git rebase --abort");
    system("git checkout master");
  }
  else {
    system("git checkout master");
    system("git merge $branch");
    system("git branch -d $branch");
  }

  unlock_dir("$lists_dir");

//  $cat=new category($id);
//  $cat->compile();
  global $fifo_path;
  if(file_exists($fifo_path)) {
    $f=fopen($fifo_path, "w");
    fputs($f, "compile $id\n");
    fclose($f);
  }

  if($error) {
    return array("status"=>"merge failed", "branch"=>$branch, "id"=>$id);
  }
  else {
    return array("status"=>true, "id"=>$id);
  }
}

// category_list()
// lists all categories
//
// parameters:
// $lang      language
//
// return:
// array('category_id'=>'name', ...)
function category_list($lang="en") {
  global $lists_dir;

  if($state=category_check_state()!==true) {
    return array('status'=>$state);
  }

  $ret=array();
  lock_dir($lists_dir);

  $d=opendir("$lists_dir");
  while($f=readdir($d)) {
    if(preg_match("/^(.*)\.xml$/", $f, $m)) {
      $x=new DOMDocument();
      $x->loadXML(file_get_contents("$lists_dir/$f"));
      $tags=new tags();
      $tags->readDOM($x->firstChild);

      if($tags->get("hide")!="yes") {
	$ret[$m[1]]=$tags;
      }
    }
  }
  closedir($d);

  unlock_dir($lists_dir);

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
  global $lists_dir;

  if($state=category_check_state()!==true) {
    return array('status'=>$state);
  }

  lock_dir($lists_dir);

  if($param[branch]) {
    chdir($lists_dir);
    system("git checkout $param[branch]");
    system("git rebase $param[version]");

    $content=file_get_contents("$lists_dir/$id.xml");

    $version=category_version();
    return $content;
  }
  elseif($param[version]) {
    chdir($lists_dir);
    $p=popen("git show $param[version]:$id.xml", "r");
    while($r=fgets($p)) {
      $content.=$r;
    }
    pclose($p);
    $version=$param[version];
  }
  else {
    if(!file_exists("$lists_dir/$id.xml")) {
      unlock_dir($lists_dir);
      print "File $id not found!\n";
      exit;
    }

    $content=file_get_contents("$lists_dir/$id.xml");
    $version=category_version();
  }

  $file=new DOMDocument();
  $file->loadXML($content);

  $l=$file->getElementsByTagName("category");
  for($i=0; $i<$l->length; $i++) {
    $l->item($i)->setAttribute("version", $version);
  }

  unlock_dir($lists_dir);
  return $file->saveXML();
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
function category_history($id, $param=array()) {
  global $lists_dir;
  $ret=array();

  if($state=category_check_state()!==true) {
    return array('status'=>$state);
  }

  chdir($lists_dir);

  $p=popen("git log --pretty=medium $id.xml", "r");
  while($r=fgets($p)) {
    $r=trim($r);
    if(preg_match("/^commit (.*)$/", $r, $m)) {
      $ret[]=array("version"=>$m[1]);
    }
    elseif(preg_match("/^([A-Za-z0-9]+):\h(.*)$/", $r, $m)) {
      $ret[sizeof($ret)-1][$m[1]]=$m[2];
    }
  }
  pclose($p);

  return $ret;
}

function categories_init() {
  global $default_categories;
  if(isset($default_categories))
    html_export_var(array("default_categories"=>$default_categories));
}

register_hook("init", "categories_init");
