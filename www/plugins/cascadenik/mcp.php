<?
global $cascadenik_fontsets;
$cascadenik_fontsets=array(
  "book"=>array("DejaVu Sans Book", "unifont Medium"),
  "bold"=>array("DejaVu Sans Bold", "unifont Medium"),
  "oblique"=>array("DejaVu Sans Oblique", "unifont Medium"),
);

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

  rename("$file_noext.xml", "$file_noext.mapnik");

  call_hooks("cascadenik_compiled", "$file_noext.mapnik");
}
