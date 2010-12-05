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

$node=$map->firstChild;
$last_layer=null;
$last_layer_table="";
while($node) {
  $next_node=$node->nextSibling;
  
  if(isset($node->tagName)&&($node->tagName=="Layer")) {
    $x=new DOMXPath($dom);
    $nl=$x->query("Datasource/Parameter[@name='table']", $node);
    if($nl->length)
      $layer_table=$nl->item(0)->firstChild->nodeValue;

      fprintf($stderr, strlen($layer_table)." ".strlen($last_layer_table)."\n");

      if($layer_table==$last_layer_table) {
	$node1=$node->firstChild;
	while($node1) {
	  $next_node1=$node1->nextSibling;

	  if(isset($node1->tagName)&&($node1->tagName=="StyleName")) {
	    $last_layer->appendChild($node1);
	    $br=$dom->createTextNode("\n");
	    $last_layer->appendChild($br);
	  }

	  $node1=$next_node1;
	}

	$map->removeChild($node);
      }
      else {
	$last_layer_table=$layer_table;
	$last_layer=$node;
      }
    }
  }

  $node=$next_node;
}

print $dom->saveXML();
