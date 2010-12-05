#!/usr/bin/php
<?
if(sizeof($argv)<2) {
  print "{$argv[0]} file\n";
  exit;
}

$stderr = fopen('php://stderr', 'w'); 

$autoinc=0;
$dom=new DOMDocument();
$dom->loadXML(file_get_contents($argv[1]));
$map=$dom->firstChild;

$avail_datasources=array();
$x=new DOMXPath($dom);
$datasource_list=$x->query("Layer/Datasource", $map);
for($di=0; $di<$datasource_list->length; $di++) {
  $datasource=$datasource_list->item($di);
  $layer=$datasource->parentNode;

  $table_list=$x->query("Parameter[@name='table']", $datasource);
  if($table_list->length) {
    $table=$table_list->item(0)->firstChild->nodeValue;
    if(isset($avail_datasources[$table])) {
      $id=$avail_datasources[$table];
    }
    else {
      $id=++$autoinc;
      $avail_datasources[$table]=$id;
      $datasource->setAttribute("name", "datasource $id");
      $map->insertBefore($datasource, $map->firstChild);
    }

    $ds_ref=$dom->createElement("Datasource");
    $ds_ref->setAttribute("base", "datasource $id");
    $layer->appendChild($ds_ref);
  }
}

print $dom->saveXML();
