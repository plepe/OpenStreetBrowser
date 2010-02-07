#!/usr/bin/php
<?
require_once("conf.php");
require_once("src/wiki_stuff.php");

$columns=array(
  "Categories"=>array("category", "bg-color", "fg-color"),
  "Values"=>array("keys", "desc", "category", "network", "icon", "overlay"),
  "Importance"=>array("key", "onlyicon", "icontext")
);
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
$img_list=array();

$wiki_data=read_wiki();

$category_list=array();
foreach($wiki_data["Categories"] as $src) {
  $category_list[$src[category]]=$src;
}

foreach($wiki_data["Importance"] as $src) {
  $scales[$src[key]]=$src;
}

$process_overlays=array();
// Download symbols
$img_list=array();
$process_overlays=array();
// Download symbols
$img_list=array();
foreach($wiki_data["Values"] as $src) {
  if(ereg("^([a-z:_]*)\(", $src[overlay], $m))
    $process_overlays[$m[1]]=1;

  if(eregi("\[\[Image:(.*)\]\]", $src[icon], $m)) {
    $icon=strtr($m[1], array(" "=>"_"));
    if(!$img_list[$icon]) {
      $img_data=gzfile("$wiki_img$icon");

      if(!$img_data)
	print "Can't open $wiki_img$icon\n";

      unset($icon_path);
      foreach($img_data as $r) {
	if(eregi("<div class=\"fullImageLink\" .*<a href=\"([^\"]*)\">", $r, $m)) {
	  print $m[1]."\n";
	  $img=file_get_contents("$wiki_imgsrc$m[1]");
	  if(!$img)
	    print "Can't download $wiki_imgsrc$m[1]\n";
	  $img_d=fopen("$symbol_path/$icon", "w");
	  fwrite($img_d, $img);
	  fclose($img_d);

	  $icon_path="$symbol_path/$icon";

	  if(eregi("^(.*)\.svg$", $icon, $m)) {
	    system("convert -background none '$symbol_path/$icon' 'PNG:$symbol_path/$m[1].png'");
	    $icon_path="$symbol_path/$m[1].png";
	  }

	  $img_list[$icon]=$icon_path;
	}
      }

      if(!$icon_path) {
	print "not found!\n";
	exit;
      }
    }
  }
}
$process_overlays=array_keys($process_overlays);

foreach($process_overlays as $overlay) {
  print "* $overlay\n";
  $values_list=array();
  foreach($wiki_data["Values"] as $src) {
    if(eregi("$overlay\(([0-9]*)\)", $src[overlay], $m)) {
      $values_list[$m[1]][]=array("category"=>$src[category], "data"=>$src, "priority"=>$m[1]);
    }
  }

  $v=array();
  for($i=0; $i<=max(array_keys($values_list)); $i++) {
    if(sizeof($values_list[$i]))
      $v=array_merge($v, $values_list[$i]);
  }
  $values_list=$v;
 //print_r($values_list);

  $index=0;
  $sql_type="";
  $sql_network="";
  $sql_desc="";
  $sql_where="";
  $sql_order_network="";
  $style_icon="";
  $style_text="";
  $list_columns=array();

  foreach($values_list as $values_data) {
    $data=$values_data[data];
    $icon=$data[icon];
    $icon_prefix=false;

    if(eregi("\[\[Image:(.*)\]\]", $icon, $m)) {
      $icon=strtr($m[1], array(" "=>"_"));
      if($img_list[$icon]) {
	$icon=$img_list[$icon];
      }
      else
	unset($icon);
    }
    else
      unset($icon);

    if($icon)
      $imgsize=getimagesize($icon);
    else
      $imgsize=array(0, -12);
    if(!($category_data=$category_list[$data[category]])) {
      $category_data=$category_list[substr($data[category], 0, strpos($data[category], "/"))];
    }
    foreach($scales as $network=>$zoom) {
      if($icon) {
	$style_icon.="  <Rule>\n";
	$style_icon.="    <Filter>[type] = $index and [network] = '$network'</Filter>\n";
	$style_icon.="    <MaxScaleDenominator>{$scales_levels[$zoom[onlyicon]]}</MaxScaleDenominator>\n";
	$style_icon.="    <PointSymbolizer file = \"{$icon}\" type=\"png\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" allow_overlap=\"true\"/>\n";
	$style_icon.="  </Rule>\n";
      }
      $style_text.="  <Rule>\n";
      $style_text.="    <Filter>[type] = $index and [network] = '$network'</Filter>\n";
      $style_text.="    <MaxScaleDenominator>{$scales_levels[$zoom[onlyicon]]}</MaxScaleDenominator>\n";
      $style_text.="    <TextSymbolizer dy=\"".ceil($imgsize[1]/2+3)."\" face_name=\"DejaVu Sans Book\" fill=\"#000000\" name=\"name\" placement=\"point\" size=\"10\"";
      if($category_data["bg-color"])
        $style_text.=" halo_fill=\"{$category_data["bg-color"]}\" halo_radius=\"1\"";
      $style_text.="/>\n";
      $style_text.="    <TextSymbolizer dy=\"".ceil($imgsize[1]/2+10+3)."\" face_name=\"DejaVu Sans Book\" fill=\"#000000\" name=\"desc\" placement=\"point\" size=\"8\"";
      if($category_data["bg-color"])
        $style_text.=" halo_fill=\"{$category_data["bg-color"]}\" halo_radius=\"1\"";
      $style_text.="/>\n";
      $style_text.="  </Rule>\n";
    }

    $sql_type.="  WHEN ";
    $sql_network.="  WHEN ";

    $statement=parse_wholekey($data[keys], &$list_columns);
    $sql_type.=$statement;
    $sql_network.=$statement;
      $sql_desc.="  WHEN ";
      $sql_desc.=$statement;
    if($data[desc])
      $sql_desc.=" THEN \"$data[desc]\"\n";
    else
      $sql_desc.=" THEN ''\n";
    $sql_type.=" THEN $index\n";
    $sql_network.=" THEN '{$data[network]}'\n";

    $index++;
  }

  $sql_where=array();
  foreach($list_columns as $key=>$values) {
    // if all values are "positive" (no 'not null' and no 'not in (...)') 
    // then we can make use of indices
    $pos=true;
    foreach($values as $v) {
      if((substr($v, 0, 1)=="!")||($v=="*")) {
	$pos=false;
      }
    }

    if($pos)
      $sql_where[]="\"$key\" in ('".implode("', '", array_unique($values))."')";
  }

  $sql_where=implode(" or ", $sql_where);
  $sql_order_network=<<<EOT
    (CASE WHEN "network"='international' THEN 4
	  WHEN "network"='national' THEN 3
	  WHEN "network"='regional' THEN 2
	  WHEN "network"='urban' THEN 1
	  WHEN "network"='local' THEN 0 END)
EOT;

  // XML doesn't like < and >
  $sql_desc=strtr($sql_desc, array("<"=>"&lt;", ">"=>"&gt;"));
  $sql_type=strtr($sql_type, array("<"=>"&lt;", ">"=>"&gt;"));
  $sql_network=strtr($sql_network, array("<"=>"&lt;", ">"=>"&gt;"));

  $file=fopen("$style_path/$overlay.layer", "w");
  fputs($file, "  <Style name=\"foobar\">\n");
  fputs($file, $style_icon);
  fputs($file, "  </Style>\n");
  fputs($file, "  <Style name=\"foobar text\">\n");
  fputs($file, $style_text);
  fputs($file, "  </Style>\n");
  fputs($file, "  <Layer name=\"foobar1\" srs=\"+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs +over\" status=\"on\">\n");
  fputs($file, "  <StyleName>foobar</StyleName>\n");
  fputs($file, "    <Datasource>\n");
  fputs($file, "      <Parameter name=\"type\">postgis</Parameter>\n");
  fputs($file, "      <Parameter name=\"dbname\">$db_name</Parameter>\n");
  fputs($file, "      <Parameter name=\"table\">(select * from (select name, way, (CASE\n$sql_type\n  END) as type, (CASE\n  WHEN \"network\" in ('international', 'national', 'regional', 'urban', 'suburban', 'local') then \"network\"\n$sql_network\n  END) as network, (CASE\n  $sql_desc END) as desc from planet_osm_point where $sql_where union select name, way, (CASE\n$sql_type\n  END) as type, (CASE\n  WHEN \"network\"  in ('international', 'national', 'regional', 'urban', 'suburban', 'local') then \"network\"\n$sql_network\n  END) as network, (CASE\n  $sql_desc END) as desc from planet_osm_polygon where $sql_where) as x1 order by $sql_order_network asc) as x2</Parameter>\n");
  fputs($file, "      <Parameter name=\"estimate_extent\">false</Parameter>\n");
  fputs($file, "      <Parameter name=\"extent\">-20037508,-19929239,20037508,19929239</Parameter>\n");
  fputs($file, "    </Datasource>\n");
  fputs($file, "  </Layer>\n");
  fputs($file, "  <Layer name=\"foobar2\" srs=\"+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs +over\" status=\"on\">\n");
  fputs($file, "  <StyleName>foobar text</StyleName>\n");
  fputs($file, "    <Datasource>\n");
  fputs($file, "      <Parameter name=\"type\">postgis</Parameter>\n");
  fputs($file, "      <Parameter name=\"dbname\">$db_name</Parameter>\n");
  fputs($file, "      <Parameter name=\"table\">(select * from (select name, way, (CASE\n$sql_type\n  END) as type, (CASE\n  WHEN \"network\" in ('international', 'national', 'regional', 'urban', 'suburban', 'local') then \"network\"\n$sql_network\n  END) as network, (CASE\n  $sql_desc END) as desc from planet_osm_point where $sql_where union select name, way, (CASE\n$sql_type\n  END) as type, (CASE\n  WHEN \"network\" in ('international', 'national', 'regional', 'urban', 'suburban', 'local') then \"network\"\n$sql_network\n  END) as network, (CASE\n  $sql_desc END) as desc from planet_osm_polygon where $sql_where) as x1 order by $sql_order_network desc) as x2</Parameter>\n");
  fputs($file, "      <Parameter name=\"estimate_extent\">false</Parameter>\n");
  fputs($file, "      <Parameter name=\"extent\">-20037508,-19929239,20037508,19929239</Parameter>\n");
  fputs($file, "    </Datasource>\n");
  fputs($file, "  </Layer>\n");
}
