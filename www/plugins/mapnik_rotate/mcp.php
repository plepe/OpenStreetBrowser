<?
function mapnik_rotate_rule($rule, $filter) {
  if(preg_match("/(.*)not \(\(\[(angle[:_a-zA-Z0-9]*)\]=([0-9]*)\)\)(.*)/", $filter->nodeValue, $m)) {
//    $filter->nodeValue="$m[1]([$m[2]] is null)$m[4]";
    $rule->parentNode->removeChild($rule);
  }
  else if(preg_match("/^(.*)\(\[(angle[:_a-zA-Z0-9]*)\]=([0-9]*)\)(.*)$/", $filter->nodeValue, $m)) {
    for($i=0; $i<$m[3]; $i++) {
      $n=$rule->cloneNode(true);
      $n->setAttribute("name", $n->getAttribute("name")."-$i");
      
      $f=$n->getElementsByTagName("Filter");
      $f=$f->item(0);
      $f->nodeValue="$m[1]([$m[2]]=$i)$m[4]";

      $p=$n->getElementsByTagName("PointSymbolizer");
      $p=$p->item(0);
      $a=($i*360/$m[3]);
      $p->setAttribute("transform", "rotate($a)");
      $p->setAttribute("file", substr($p->getAttribute("file"), 0, -4).".svg");
      $p->setAttribute("type", "svg");

      $rule->parentNode->appendChild($n);
    }

    $rule->parentNode->removeChild($rule);
  }
}

function mapnik_rotate_compiled($file) {
  $dom=new DOMDocument();
  $dom->load($file);
   
  print "Starting Mapnik Rotate\n";
  $list=array();
  $filters=$dom->getElementsByTagName("Filter");
  for($i=0; $i<$filters->length; $i++) {
    $filter=$filters->item($i);

    $list[]=$filter;
  }

  foreach($list as $filter) {
    $rule=$filter->parentNode;

    mapnik_rotate_rule($rule, $filter);
  }

  $dom->save($file);

  return;
}

register_hook("cascadenik_compiled", "mapnik_rotate_compiled");
