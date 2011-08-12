<?
function wikipedia_streetnames_parse($article, $object) {
  $name=$object->tags->get("name");

  $article=explode("\n", $article);
  foreach($article as $line) {
    if(preg_match("/^\* *[']*([^']*)[']*[:,] *(.*)$/", $line, $m)) {
      $parse_name=$m[1];
      $parse_text=$m[2];

      if(preg_match("/^\[\[([^\|]+\|)?([^\|]+)\]\]$/", $parse_name, $m))
	$parse_name=$m[2];

      if($parse_name==$name) {
	return wikipedia_parse($parse_text);
      }
    }
  }
}

function wikipedia_streetnames_info($info_ret, $object) {
  $text="";

//$text=print_r($object, 1);
  if(!$object->tags->get("highway"))
    return;

  $res=sql_query("select * from osm_polygon where osm_way && geomfromtext('{$object->data['way']}', 900913) and CollectionIntersects(osm_way, geomfromtext('{$object->data['way']}', 900913)) and osm_tags @> 'boundary=>administrative' order by parse_number(osm_tags->'admin_level') desc");
  while($elem=pg_fetch_assoc($res)) {
    $boundary=load_object($elem['osm_id']);

    $res=wikipedia_get_lang_page($boundary, "wikipedia:streetnames");
    if($res) {
      if($article=wikipedia_get_article($boundary, $res['page'], $res['lang'])) {
	$text=wikipedia_streetnames_parse($article, $object);
	if($text) {
	  $text.="<br>".lang("source").": <a class='external' href='".wikipedia_url($boundary, $res['page'], $res['lang'])."'>Wikipedia</a>\n";
	  $info_ret[]=array("head"=>"wikipedia_streetnames", "content"=>$text);
	  return;
	}
      }
    }
  }
}


register_hook("info", "wikipedia_streetnames_info");
