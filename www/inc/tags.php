<?
function parse_array($text) {
  if((substr($text, 0, 1)!="{")||(substr($text, -1)!="}"))
    return array();

  $parts=explode(",", substr($text, 1, -1));
  $ret=array();

  $i=0;
  do {
    if(substr($parts[$i], 0, 1)=="\"") {
      if(substr($parts[$i], -1)=="\"")
	$t1=substr($parts[$i], 1, -1);
      else {
	$t1=substr($parts[$i], 1);
	do {
	  $i++;
	  $t1.=",".$parts[$i];
	} while(substr($parts[$i], -1)!="\"");
	$t1=substr($t1, 0, -1);
      }

      $ret[]=$t1;
    }
    else {
      $ret[]=$parts[$i];
    }
    $i++;
  }
  while($i<sizeof($parts));

  return $ret;
}

function parse_tags($text) {
  $arr=parse_array($text);
  $ret=array();

  for($i=0; $i<sizeof($arr); $i+=2) {
    $ret[$arr[$i]]=$arr[$i+1];
  }
  
  return $ret;
}

function parse_tags_old($text) {
  $text=stripslashes($text);
  $tags=array();
  $mode=0;
  $tag_key="";
  $tag_value="";
  for($i=0; $i<strlen($text)-1; $i++) {
    $c=substr($text, $i, 1);
    if($mode==0) {
      if($c=="{") 
	$mode=1;
    }
    elseif($mode==1) {
      if($c==",")
	$mode=2;
      else
	$tag_key.=$c;
    }
    elseif($mode==2) {
      if(($c=="\"")&&($tag_value==""))
	$mode=3;
      elseif($c==",") {
        $tags[$tag_key]=$tag_value;
	$tag_key="";
	$tag_value="";
	$mode=1;
      }
      else
	$tag_value.=$c;
    }
    elseif($mode==3) {
      if($c=="\"")
	$mode=4;
      else
	$tag_value.=$c;
    }
    elseif($mode==4) {
      $mode=1;
      $tags[$tag_key]=$tag_value;
      $tag_key="";
      $tag_value="";
    }
  }

  if($tag_key)
    $tags[$tag_key]=$tag_value;

  return $tags;
}

