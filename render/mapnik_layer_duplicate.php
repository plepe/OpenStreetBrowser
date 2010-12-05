#!/usr/bin/php
<?
if(sizeof($argv)<2) {
  print "{$argv[0]} file\n";
  exit;
}

$stderr = fopen('php://stderr', 'w'); 

$dom=new DOMDocument();
$dom->loadXML(file_get_contents($argv[1]));
$map=$dom->firstChild;

// Get list of all layers and styles
$style_layer_list=array();
$list=$dom->getElementsByTagName("Layer");
for($i=0; $i<$list->length; $i++) {
  $style_layer_list[]=$list->item($i);
}

$style_list=array();
$list=$dom->getElementsByTagName("Style");
for($i=0; $i<$list->length; $i++) {
  $style_list[]=$list->item($i);
}

$node=$map->firstChild;
while($node) {
  $next_node=$node->nextSibling;

  if((isset($node->tagName))&&
     (($node->tagName=="Layer")||($node->tagName=="Style"))) {
    $map->removeChild($node);
  }

  $node=$next_node;
}

// Repeat for each level of layers
For($layer=-5; $layer<=5; $layer++) {
  // Clone all Styles, adapt names
  foreach($style_list as $style) {
    $cl=$style->cloneNode(true);

    fprintf($stderr, "Processing Style \"".$cl->getAttribute("name")." - layer $layer\"\n");

    $map->appendChild($cl);
    $cl->setAttribute("name", $cl->getAttribute("name")." - layer $layer");

    // Change Filters in all Rules
    $rule_list=$cl->getElementsByTagName("Rule");
    for($i=0; $i<$rule_list->length; $i++) {
      $rule=$rule_list->item($i);
      $filter_list=$rule->getElementsByTagName("Filter");
      for($j=0; $j<$filter_list->length; $j++) {
	$filter=$filter_list->item($j);

	$filter->firstChild->nodeValue.=" and [layer]=$layer";
      }
    }
  }

  foreach($style_layer_list as $l) {
    $cl=$l->cloneNode(true);
    $map->appendChild($cl);

    fprintf($stderr, "Processing Layer \"".$cl->getAttribute("name")." - layer $layer\"\n");

    $cl->setAttribute("name", $cl->getAttribute("name")." - layer $layer");

    $list=$cl->getElementsByTagName("StyleName");
    for($j=0; $j<$list->length; $j++) {
      $list->item($j)->firstChild->nodeValue.=" - layer $layer";
    }
  }
}

print $dom->saveXML();
