<?
class tags {
  private $data;
  public $compiled_tags=array();

  function __construct($parse_text_k, $parse_text_v=null) {
    if(is_array($parse_text_k)) {
      $this->data=$parse_text_k;
    }
    else {
      $ks=parse_array($parse_text_k);
      $vs=parse_array($parse_text_v);

      $this->data=array();
      for($i=0; $i<sizeof($ks);$i++) {
	$this->data[$ks[$i]]=$vs[$i];
      }
    }
  }

  function get($k) {
    return $this->data[$k];
  }

  function get_lang($k) {
    global $lang;

    if($ret=($this->data["$k:$lang"]))
      return $ret;

    return $this->data[$k];
  }

  function get_available_languages($key) {
    $list=array();

    foreach($this->data as $k=>$v) {
      if(ereg("^$key:(.*)$", $k, $m)) {
	$list[$m[1]]=$v;
      }
    }

    return $list;
  }

  function set($k, $v) {
    $this->data[$k]=$v;
  }

  function erase($k) {
    unset($this->data[$k]);
  }

  function data() {
    return $this->data;
  }

  function get_xml($obj, $root) {
    foreach($this->data as $key=>$value) {
      $subnode=$root->createElement("tag");
      $subnode->setAttribute("k", $key);
      $subnode->setAttribute("v", $value);
      $obj->appendChild($subnode);
    }
  }

  function compile_text($text) {
    $match=0;

    while(ereg("^(.*)(%[^%]+%)(.*)$", $text, $m)) {
      $tag=substr($m[2], 1, -1);
      if($rep=$this->get_lang($tag))
	$match++;
      $text=$m[1].$rep.$m[3];
      $this->compiled_tags[]=$tag;
    }

    if(!$match)
      return;

    while(ereg("^(.*)(#[^#]+#)(.*)$", $text, $m)) {
      $text=$m[1].lang(substr($m[2], 1, -1)).$m[3];
    }

    return $text;
  }
}

function parse_array($text, $prefix="") {
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

      $ret[]=stripslashes("$prefix$t1");
    }
    else {
      if($parts[$i]=="NULL")
        $parts[$i]="";
      $ret[]=stripslashes("$prefix$parts[$i]");
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


