<?
/* this file holds functions to read config from wiki-stylesheet
*/

function get_row($r, $f) {
  $row=array();

  if(substr($r, 0, 2)=="|-")
    return null;
  if(substr($r, 0, 2)=="|}")
    return null;

  do {
    $r=trim($r);
    if($r=="|")
      $cells=array("");
    else {
      $cells=explode("||", $r);
      if($cells[0]=="")
	array_splice($cells, 0, 1);
      elseif(ereg("^\|.*\|(.*)$", $r, $m))
      $cells[0]=$m[1];
    }

    $row=array_merge($row, $cells);
    $r=fgets($f);
  } while(!in_array(substr($r, 0, 2), array("|-", "|}")));

  return $row;
}

function read_wiki() {
  global $wiki_stylesheet;
  global $columns;
  $data=array();

  $f=fopen($wiki_stylesheet, "r");
  unset($this_part);

  while($r=fgets($f)) {
    $r=chop($r);

    if(ereg("==([^=]+)==", $r, $m)) {
      $mode=0;
      $part=trim($m[1]);
    }

    if(substr($r, 0, 1)=="|") {
      $this_row=get_row($r, $f);

      if($this_row&&($columns[$part])) { 
	$src=array();
	foreach($this_row as $i=>$r2) {
	  $src[$columns[$part][$i]]=trim($r2);
	}
      
	$data[$part][]=$src;
      }
    }
  }

  fclose($f);
  return $data;
}

function parse_key($key, &$list_columns) {
  $ret="";

  if(eregi("\{\{Tag\|(.*)\|(.*)\}\}", $key, $m)) {
    $ret="\"$m[1]\"='$m[2]'";
    if($i==0)
      $list_columns[$m[1]][]=$m[2];
  }
  elseif(eregi("\{\{Tag\|([^\}]*)\}\}", $key, $m)) {
    $ret="\"$m[1]\" is not null";
    if($i==0)
      $list_columns[$m[1]][]="*";
  }
  elseif(eregi("^([a-z0-9_]+)(=|!=|<|>|<=|>=)(.*)$", $key, $m)) {
    if(($m[2]=="=")&&($m[3]=="*")) {
      $ret="\"$m[1]\" is not null";
      if($i==0)
	$list_columns[$m[1]][]="*";
    }
    elseif(($m[2]=="=")&&($m[3]=="")) {
      $ret="(\"$m[1]\"='' or \"$m[1]\" is null)";
      if($i==0)
	$list_columns[$m[1]][]="*";
    }
    elseif($m[2]=="=") {
      $ret="\"$m[1]\"='$m[3]'";
      if($i==0)
	$list_columns[$m[1]][]=$m[3];
    }
    elseif($m[2]=="!=") {
      $ret="\"$m[1]\" not in ('".implode("', '", explode(";", $m[3]))."')";
      if($i==0) foreach(explode(";", $m[3]) as $m3) {
	$list_columns[$m[1]][]="!$m3";
      }
    }
    elseif(in_array($m[2], array(">", "<", ">=", "<="))) {
      if(eregi("^(.*)(>|<|>=|<=|=)(.*)$", $m[3], $m1)) {
	$ret="(\"$m[1]\" similar to '[0-9]+' and cast(\"$m[1]\" as int)$m[2]$m1[1] and cast(\"$m[1]\" as int)$m1[2]$m1[3])";
      }
      else {
	$ret="(\"$m[1]\" similar to '[0-9]+' and cast(\"$m[1]\" as int)$m[2]$m[3])";
      }
	if($i==0) foreach(explode(";", $m[3]) as $m3) {
	  $list_columns[$m[1]][]="*";
      }
    }
  }

  return $ret;
}

function parse_wholekey($k, &$list_columns) {
  $ret=array();

  $keys_list=explode(",", $k);
  foreach($keys_list as $keys_part) {
    $keys=explode(" ", $keys_part);

    $l=array();
    foreach($keys as $i=>$k) {
      $l1=parse_key($k, $list_columns);
      if($l1) $l[]=$l1;
    }

    $ret[]=implode(" AND ", $l);
  }

  if(sizeof($ret)>1)
    return "(".implode(") OR (", $ret).")";
  else
    return $ret[0];
}
