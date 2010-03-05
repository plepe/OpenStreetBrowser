<?
function wikipedia_parse($text) {
  while(eregi("^(.*)\[\[([^\|\]*\|)?([^\[\|]*)\]\](.*)", $text, $m)) {
    $text=$m[1].$m[3].$m[4];
  }
  while(eregi("^(.*)'''([^']*)'''(.*)$", $text, $m)) {
    $text=$m[1].$m[2].$m[3];
  }
  return $text;
}

function ext_wikipedia($object) {
  $ret="";

  foreach($object->tags->get_available_languages("wikipedia") as $lang=>$url) {
//  if(!$url=$object->tags->get("wikipedia:de"))
//    $url=$object->tags->get("wikipedia");

    if($url=="yes")
      $url=$object->tags->get("name:$lang")||$object->tags->get("name");

    $url=strtr($url, array(" "=>"_"));

    ini_set("user_agent", "OpenStreetBrowser Wikipedia Parser");
    if(@$f=fopen("http://$lang.wikipedia.org/w/index.php?title=$url&action=raw", "r")) {
      $text=""; unset($img);
      $enough=0;
      while(($r=fgets($f))&&(!$enough)) {
    //    if(!$img&&eregi("\[\[Bild:([^\|\]]*)[\|\]]", $r, $m)) {
	$r=chop($r);
	if(($r=="")||
           (preg_match("/^<!--/", $r))
	  ) {
	}
	elseif(!$img&&eregi("\[\[(Bild|Datei):([^\|]*\.(png|jpg|gif))", $r, $m)) {
	  $img=$m[2];
	  $img="<img src='http://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/$img/100px-$img' align='left'>\n";
	}
	elseif(!ereg("^[\|\}\{\[\!]", $r)) {
	  $text.=wikipedia_parse($r);
	  $enough=1;
	}
      }

      if($text) {
	$ret.="<u>$lang.wikipedia.org:</u><br>\n$img$text<br/><a class='external' href='http://$lang.wikipedia.org/wiki/$url'>read more</a><br>";
      }

      fclose($f);
    }
  }

  return $ret;
}
