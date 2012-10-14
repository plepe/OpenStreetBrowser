<?
global $cascadenik_fontsets;
$cascadenik_fontsets=array(
  "book"=>array("DejaVu Sans Book", "unifont Medium"),
  "bold"=>array("DejaVu Sans Bold", "unifont Medium"),
  "oblique"=>array("DejaVu Sans Oblique", "unifont Medium"),
);

function cascadenik_style_reorder($file) {
  $content=new DOMDocument();
  $content->loadXML(file_get_contents($file));

  print "Cascadenik:: Re-Ordering styles in Layers\n";

  $layers=$content->getElementsByTagName("Layer");
  for($i=0; $i<$layers->length; $i++) {
    $layer=$layers->item($i);
    printf("* %s\n", $layer->getAttribute("name"));

    $style_list=array();
    $styles=$layer->getElementsByTagName("StyleName");
    for($j=0; $j<$styles->length; $j++) {
      $style=$styles->item($j);
      $style_name=$style->firstChild->textContent;
      printf("  %s\n", $style_name);
      $style_list[]=$style;
    }

    rsort($style_list);
    foreach($style_list as $style) {
      $layer->removeChild($style);
      $layer->appendChild($style);
    }
  }

  file_put_contents($file, $content->saveXML());
}

function cascadenik_set_fontsets($file) {
  global $cascadenik_fontsets;
  $font_replace=array();

  print "Cascadenik:: Replacing font_faces by fontsets\n";

  $content=new DOMDocument();
  $content->loadXML(file_get_contents($file));

  // find Map and its first child
  $map=$content->getElementsByTagName("Map");
  if(!$map->length)
    return;
  $map=$map->item(0);
  $first_el=$map->firstChild;

  foreach($cascadenik_fontsets as $name=>$fonts) {
    // insert fontset clauses
    $fontset=$content->createElement("FontSet");
    $fontset=$map->insertBefore($fontset, $first_el);
    $fontset->setAttribute("name", $name);

    // insert list of possible face names to each fontset
    foreach($fonts as $font) {
      $f=dom_create_append($fontset, "Font", $content);
      $f->setAttribute("face_name", $font);

      $font_replace[$font]=$name;
    }
  }

  // process full file and search for TextSymbolizers
  $syms=$content->getElementsByTagName("TextSymbolizer");
  for($i=0; $i<$syms->length; $i++) {
    $sym=$syms->item($i);

    // if a face_name is found replace by fontset_name
    if(($face_name=$sym->getAttribute("face_name"))&&($font_replace[$face_name])) {
      $sym->removeAttribute("face_name");
      $sym->setAttribute("fontset_name", $font_replace[$face_name]);
    }

    // if a face-name is found replace by fontset_name (mapnik >0.7.2)
    if(($face_name=$sym->getAttribute("face-name"))&&($font_replace[$face_name])) {
      $sym->removeAttribute("face-name");
      $sym->setAttribute("fontset_name", $font_replace[$face_name]);
    }

    // as cascadenik ignores text-max-char-angle-delta, we just put it to
    // any TextSymbolizer. Is there a reason why we wouldn't want that?
    $sym->setAttribute("max_char_angle_delta", 10);
  }

  file_put_contents($file, $content->saveXML());
}

/* you can add additional parameters to layers by
 * adding "/*layer* .... * /" (remove the " " between * and /)
 * to the sql-query
 * (no that's not nice, but cascadenik doesn't make it easy to do unexpected
 * things)
 */
function cascadenik_add_layer_params($file) {
  global $cascadenik_fontsets;
  $font_replace=array();

  print "Cascadenik:: Adding additional layer parameters\n";

  $content=new DOMDocument();
  $content->loadXML(file_get_contents($file));

  // find all Layers Map and its first child
  $layers=$content->getElementsByTagName("Layer");
  for($i=0; $i<$layers->length; $i++) {
    $layer=$layers->item($i);

    $params=$layer->getElementsByTagName("Parameter");
    for($j=0; $j<$params->length; $j++) {
      $param=$params->item($j);

      if($param->getAttribute("name")=="table") {
	$sql=$param->textContent;

	if(preg_match("/\/\*layer\*(.*)\*\//", $sql, $m)) {
	  $x=new DOMDocument();
	  $x->loadXML("<?xml version='1.0'?"."><tmp $m[1]/>");
	  foreach($x->firstChild->attributes as $attr) {
	    $layer->setAttribute($attr->nodeName, $attr->nodeValue);
	  }
	}
      }
    }
  }

  file_put_contents($file, $content->saveXML());
}

function cascadenik_compile($file, $path=null) {
  $file_noext=substr($file, 0, strrpos($file, "."));
  if(!$path)
    $path=substr($file, 0, strrpos($file, "/"));

  call_hooks("cascadenik_compile", &$file, $path);

  print "Cascadenik process file $file\n";
  system("cascadenik-compile.py $file $file_noext.xml");

  cascadenik_set_fontsets("$file_noext.xml");
  cascadenik_style_reorder("$file_noext.xml");
  cascadenik_add_layer_params("$file_noext.xml");

  rename("$file_noext.xml", "$file_noext.mapnik");

  call_hooks("cascadenik_compiled", "$file_noext.mapnik");
}
