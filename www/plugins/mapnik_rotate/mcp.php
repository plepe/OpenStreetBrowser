<?
function mapnik_rotate_rule($rule, $filter) {
  if(preg_match("/(.*)not \(\(\[(angle[:_a-zA-Z0-9]*)\]=([0-9]*)\)\)(.*)/", $filter->nodeValue, $m)) {
//    $filter->nodeValue="$m[1]([$m[2]] is null)$m[4]";
    $rule->parentNode->removeChild($rule);
  }
  else if(preg_match("/^(.*)\(\[(angle[:_a-zA-Z0-9]*)\]=([0-9]*)\)(.*)$/", $filter->nodeValue, $m)) {
    // load src image (the svg version for sure)
    $p=$rule->getElementsByTagName("PointSymbolizer");
    $p=$p->item(0);

    $src_file=substr($p->getAttribute("file"), 0, -4);
    $src_svg=new DOMDocument();
    if(!($src_svg->load("$src_file.svg"))) {
      print "Failed loading $src_file.svg\n";
      exit;
    }
    print "Loading $src_file.svg\n";

    // get size and find center to rotate
    $size=getimagesize("$src_file.png");
    $center=array($size[0]/2.0, $size[1]/2.0);

    // find root g element
    $g_list=array();
    $check_list=$src_svg->getElementsByTagName("g");
    foreach($check_list as $check) {
      if($check->parentNode->nodeName=="svg") {
	$g_list[]=$check;
      }
      $check=$check->nextSibling;
    }

    for($i=0; $i<$m[3]; $i++) {
      $n=$rule->cloneNode(true);
      $n->setAttribute("name", $n->getAttribute("name")."-$i");
      
      $f=$n->getElementsByTagName("Filter");
      $f=$f->item(0);
      $f->nodeValue="$m[1]([$m[2]]=$i)$m[4]";

      $p=$n->getElementsByTagName("PointSymbolizer");
      $p=$p->item(0);
      $a=($i*360/$m[3]);

      if(!file_exists("$src_file-$i.png")) {
	foreach($g_list as $g) {
	  $g->setAttribute("transform", "rotate($a, $center[0], $center[1])");
	}

	$src_svg->save("$src_file-$i.svg");
	system("rsvg $src_file-$i.svg $src_file-$i.png");
      }

      $p->setAttribute("file", "$src_file-$i.png");
      $p->setAttribute("type", "png");

      $rule->parentNode->appendChild($n);
    }

    $rule->parentNode->removeChild($rule);
  }
}

function mapnik_rotate_process($file) {
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
