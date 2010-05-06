<?
$importance_levels=array("international", "national", "regional", "urban", "suburban", "local");
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
$scale_icon=array("international"=>5, "national"=>8, "regional"=>11, "urban"=>13, "suburban"=>15, "local"=>17);
$scale_text=array("international"=>8, "national"=>10, "regional"=>13, "urban"=>15, "suburban"=>16, "local"=>18);
include "postgis.php";
include "inc/categories_sql.php";

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

$got_icons=array();
function get_icon($file) {
  global $got_icons;
  global $wiki_img;
  global $wiki_imgsrc;
  global $lists_dir;

  if(isset($got_icons[$file]))
    return $got_icons[$file];

  if(!file_exists("$lists_dir/icons"))
    mkdir("$lists_dir/icons");

  $save_path="icons/".strtr($file, array());
  if(file_exists("$lists_dir/$save_path/file.png"))
    return "$save_path/file.png";

  if(preg_match("/^osm_wiki:(.*\.(.*))$/", $file, $m)) {
    $icon=strtr($m[1], array(" "=>"_"));
    $ext=$m[2];

    $img_data=gzfile("$wiki_img$icon");

    if(!$img_data)
      print "Can't open $wiki_img$icon\n";

    unset($icon_path);
    foreach($img_data as $r) {
      if(eregi("<div class=\"fullImageLink\" .*<a href=\"([^\"]*)\">", $r, $m)) {
	$img=file_get_contents("$wiki_imgsrc$m[1]");
	if(!$img)
	  print "Can't download $wiki_imgsrc$m[1]\n";

	if(!file_exists("$lists_dir/$save_path/"))
	  mkdir("$lists_dir/$save_path/");

	$img_d=fopen("$lists_dir/$save_path/file.$ext", "w");
	fwrite($img_d, $img);
	fclose($img_d);

	if(!eregi("^(.*)\.png", $icon, $m)) {
	  system("convert -background none '$lists_dir/$save_path/file.$ext' 'PNG:$lists_dir/$save_path/file.png'");
	  $icon_path="$save_path/file.png";
	}
	else
	  $icon_path="$save_path/file.$ext";

	$got_icons[$file]=$icon_path;
	return $icon_path;
      }
    }
  }

  $got_icons[$file]=null;
  return null;
}

function mapnik_style_point_icon($dom, $rule_id, $tags, $global_tags) {
  global $scales_levels;
  global $scale_icon;
  global $lists_dir;

  $icon=$tags->get("icon");
  if(!$icon)
    return null;

  $icon=get_icon($icon);
  if(!$icon)
    return null;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_icon[$tags->get("importance")]]));

  $sym=$dom->createElement("PointSymbolizer");
  $rule->appendChild($sym);
  $sym->setAttribute("file", "$lists_dir/$icon");
  $sym->setAttribute("type", "png");

  $size=getimagesize("$lists_dir/$icon");

  $sym->setAttribute("width", $size[0]);
  $sym->setAttribute("height", $size[1]);

  $style=new css("allow_overlap: true;");
  $style->apply($global_tags->get("icon_point_style"));
  $style->apply($tags->get("icon_point_style"));
  foreach(array("file", "width", "height", "type") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym);

  if($tags->get("icon_text")) {
    $sym=$dom->createElement("TextSymbolizer");
    $rule->appendChild($sym);

    $sym->setAttribute("name", "icon_text");
    $sym->setAttribute("placement", "point");

    $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 8; allow_overlap: true;");
    $style->apply($global_tags->get("icon_text_style"));
    $style->apply($tags->get("icon_text_style"));
    foreach(array("vertical_alignment", "name", "dy") as $a)
      unset($style->style[$a]);
    $style->dom_set_attributes($sym);
  }

  return $rule;
}

function mapnik_style_point_text($dom, $rule_id, $tags, $global_tags) {
  global $scales_levels;
  global $scale_text;
  global $lists_dir;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_text[$tags->get("importance")]]));

  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  $icon=$tags->get("icon");
  if($icon)
    if($icon=get_icon($icon)) {
      $size=getimagesize("$lists_dir/$icon");
      $sym->setAttribute("dy", $size[1]);
      $sym->setAttribute("vertical_alignment", "middle");
    }

  $sym->setAttribute("name", "display_name");
  $sym->setAttribute("placement", "point");

  $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ff0000; halo_radius: 1");
  $style->apply($global_tags->get("display_style"));
  $style->apply($tags->get("display_style"));
  foreach(array("vertical_alignment", "name", "dy") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym);

  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  if($icon) {
    $sym->setAttribute("dy", $size[1]+$style->style['size']);
    $sym->setAttribute("vertical_alignment", "middle");
  }

  $sym->setAttribute("name", "display_type");
  $sym->setAttribute("placement", "point");

  $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ff0000; halo_radius: 1");
  $style->apply($global_tags->get("display_style"));
  $style->apply($tags->get("display_style"));
  $style->style['size']-=2;
  foreach(array("vertical_alignment", "name", "dy") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym);

  return $rule;
}

function mapnik_style_line_icon($dom, $rule_id, $tags, $global_tags) {
  global $scales_levels;
  global $scale_icon;
  global $lists_dir;

  $icon=$tags->get("icon");
  if(!$icon)
    return null;

  $icon=get_icon($icon);
  if(!$icon)
    return null;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_icon[$tags->get("importance")]]));

  $sym=$dom->createElement("ShieldSymbolizer");
  $rule->appendChild($sym);
  $sym->setAttribute("file", "$lists_dir/$icon");
  $sym->setAttribute("type", "png");

  $size=getimagesize("$lists_dir/$icon");

  $sym->setAttribute("width", $size[0]);
  $sym->setAttribute("height", $size[1]);

  $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 8; allow_overlap: false; min_distance: 15; spacing: 200; unlock_image: true;");
  $sym->setAttribute("name", "icon_text");

  $style->apply($global_tags->get("icon_point_style"));
  $style->apply($tags->get("icon_point_style"));
  foreach(array("file", "width", "height", "type") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym);

  return $rule;
}

function mapnik_style_line_text($dom, $rule_id, $tags, $global_tags) {
  global $scales_levels;
  global $scale_text;
  global $lists_dir;

  $rule=$dom->createElement("Rule");
  $filter=$dom->createElement("Filter");
  $rule->appendChild($filter);
  $filter->appendChild($dom->createTextNode("[rule_id] = '$rule_id'"));

  $scale=$dom->createElement("MaxScaleDenominator");
  $rule->appendChild($scale);
  $scale->appendChild($dom->createTextNode(
    $scales_levels[$scale_text[$tags->get("importance")]]));

  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  $sym->setAttribute("name", "display_name");
  $sym->setAttribute("placement", "line");

  $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ff0000; halo_radius: 1; spacing: 300;");
  $style->apply($global_tags->get("display_style"));
  $style->apply($tags->get("display_style"));
  foreach(array("name") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym);

  $sym=$dom->createElement("TextSymbolizer");
  $rule->appendChild($sym);

  $sym->setAttribute("name", "display_type");
  $sym->setAttribute("placement", "line");

  $style=new css("face_name: DejaVu Sans Book; fill: #000000; size: 10; halo_fill: #ff0000; halo_radius: 1; spacing: 300;");
  $style->apply($global_tags->get("display_style"));
  $style->apply($tags->get("display_style"));
  $style->style['size']-=2;
  foreach(array("name") as $a)
    unset($style->style[$a]);
  $style->dom_set_attributes($sym);

  return $rule;
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
  $layers=array("point_icon"=>array("reverse"),
		"point_text"=>array("normal"),
		"line_text" =>array("normal"),
                "line_icon"=>array("normal"));

  sql_query("delete from categories_def where category_id='$id'");

  $dom=new DOMDocument();
  $map=$dom->createElement("Map");
  $map->setAttribute("srs", "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs +over");
  $dom->appendChild($map);
  $ret=array();
  foreach($data as $importance=>$data1) {
    foreach($data1 as $table=>$data2) {
      $style_icon=$dom->createElement("Style");
      $style_icon->setAttribute("name", "{$id}_{$importance}_{$table}_icon");
      $style_text=$dom->createElement("Style");
      $style_text->setAttribute("name", "{$id}_{$importance}_{$table}_text");
      foreach($data2['rule'] as $i=>$tags) {
	$rule_id=$data2['rule_id'][$i];
	if(in_array($table, array("point", "polygon"))) {
	  $rule=mapnik_style_point_icon($dom, $rule_id, $tags, $global_tags);
	  if(isset($rule))
	    $style_icon->appendChild($rule);
	  $rule=mapnik_style_point_text($dom, $rule_id, $tags, $global_tags);
	  if(isset($rule))
	    $style_text->appendChild($rule);
	}
	elseif(in_array($table, array("line"))) {
	  $rule=mapnik_style_line_text($dom, $rule_id, $tags, $global_tags);
	  if(isset($rule))
	    $style_text->appendChild($rule);
	  $rule=mapnik_style_line_icon($dom, $rule_id, $tags, $global_tags);
	  if(isset($rule))
	    $style_icon->appendChild($rule);
	}

	$display_name=$tags->get("display_name");
	if(!$display_name)
	  $display_name="[ref] - [name];[name];[ref];[operator]";
	$display_name=postgre_escape($display_name);

	$display_type=$tags->get("display_type");
	if(!$display_type)
	  $display_type="null";
	else
	  $display_type=postgre_escape($display_type);

	$icon_text=$tags->get("icon_text");
	if(!$icon_text)
	  $icon_text="null";
	else
	  $icon_text=postgre_escape($icon_text);

        sql_query("insert into categories_def values (".
		  postgre_escape($id).", ".postgre_escape($rule_id).", ".
		  "$display_name, $display_type, $icon_text)");
      }

      $sql=$data2['sql'];
      $sql_select=array();
      $sql_join=array();
      $sql_select[]="t.*";
      $sql_select[]="(CASE WHEN cache_name.result is null THEN tags_parse_cache(t.osm_type, t.osm_id, t.display_name_pattern) ELSE cache_name.result END) as display_name";
      $sql_join[]="left join tags_parse_cache_table cache_name on t.osm_type=cache_name.osm_type and t.osm_id=cache_name.osm_id and t.display_name_pattern=cache_name.pattern";
      $sql_select[]="(CASE WHEN cache_type.result is null THEN tags_parse_cache(t.osm_type, t.osm_id, t.display_type_pattern) ELSE cache_type.result END) as display_type";
      $sql_join[]="left join tags_parse_cache_table cache_type on t.osm_type=cache_type.osm_type and t.osm_id=cache_type.osm_id and t.display_type_pattern=cache_type.pattern";
      if($tags->get("icon_text")) {
	$sql_select[]="(CASE WHEN cache_icon.result is null THEN tags_parse_cache(t.osm_type, t.osm_id, t.icon_text_pattern) ELSE cache_icon.result END) as icon_text";
	$sql_join[]="left join tags_parse_cache_table cache_icon on t.osm_type=cache_icon.osm_type and t.osm_id=cache_icon.osm_id and t.icon_text_pattern=cache_icon.pattern";
      }
//      $sql_select[]="tags_parse(t.osm_type, t.osm_id, t.display_name_pattern) as display_name";
//      $sql_select[]="tags_parse(t.osm_type, t.osm_id, t.display_type_pattern) as display_type";

      $sql_select="\n  ".implode(",\n  ", $sql_select);
      $sql_join="\n  ".implode("\n  ", $sql_join);

      $sql="(select{$sql_select} from ($sql) as t{$sql_join}) as u";

      if(in_array($table, array("point", "polygon"))) {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_icon", $sql);
	$map_layers['point_icon'][$importance]=array($style_icon, $layer);
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_text", $sql);
	$map_layers['point_text'][$importance]=array($style_text, $layer);
      }
      else {
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_text", $sql);
	$map_layers['line_text'][$importance]=array($style_text, $layer);
	$layer=mapnik_get_layer($dom, "{$id}_{$importance}_{$table}_icon", $sql);
	$map_layers['line_icon'][$importance]=array($style_icon, $layer);
      }

    }
  }

  foreach($layers as $layer=>$direction) {
    $importance_list=$importance_levels;
    if($direction=="reverse")
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

  if(($state=category_check_state())!==true) {
    return array('status'=>$state);
  }

  if($id=="new") {
    $id=uniqid("cat_");
  }
  if(!$id) {
    print "No ID given!\n";
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
    system("git commit -m 'Fix merge' --author='webuser <web@user>'");
  }
  else {
    $branch=uniqid();

    system("git branch $branch $version");
    system("git checkout $branch");

    $f=fopen("$lists_dir/$id.xml", "w");
    fprintf($f, $file->saveXML());
    fclose($f);

    system("git add $id.xml");
    system("git commit -m 'Change category $id' --author='webuser <web@user>'");
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

  $cat=new category($id);
  $cat->compile();

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
      $ret[$m[1]]=$tags;
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
