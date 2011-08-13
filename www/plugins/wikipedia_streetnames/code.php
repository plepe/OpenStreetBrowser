<?
function wikipedia_streetnames_parse($article, $object) {
  $name=$object->tags->get("name");

  $article=explode("\n", $article);
  foreach($article as $line) {
    if(strpos($line, $name)) {
      $line=wikipedia_parse($line);
      if(preg_match("/^\* $name( \(.*\))?(,|:| - | – )/", $line, $m)) {
	return substr($line, 2);
      }
    }
  }
}

function wikipedia_streetnames_info($info_ret, $object) {
  global $data_lang;
  $text="";

  if(!$object->tags->get("highway"))
    return;

  $res=sql_query("select * from osm_polygon where osm_way && geomfromtext('{$object->data['way']}', 900913) and CollectionIntersects(osm_way, geomfromtext('{$object->data['way']}', 900913)) and osm_tags @> 'boundary=>administrative' order by parse_number(osm_tags->'admin_level') desc");
  while($elem=pg_fetch_assoc($res)) {
    $boundary=load_object($elem['osm_id']);

    $data=cache_search($boundary->id, "wikipedia:street_names:$data_lang");
    if($data) {
      $data=unserialize($data);
    }
    else {
      $data=wikipedia_get_lang_page($boundary, "wikipedia:street_names");
      $article=wikipedia_get_article($boundary, $data['page'], $data['lang']);
      $data['article']=$article;

      cache_insert($boundary->id, "wikipedia:street_names:$data_lang", 
        serialize($data), "1 hour");
    }

    if($data['article']) {
      $text.=wikipedia_streetnames_parse($data['article'], $object);
      if($text) {
	$text.="<br>".lang("source").": <a class='external' href='".wikipedia_url($boundary, $data['page'], $data['lang'])."'>Wikipedia</a>\n";
	$info_ret[]=array("head"=>"wikipedia_streetnames", "content"=>$text);
	return;
      }
    }
  }
}


register_hook("info", "wikipedia_streetnames_info");
