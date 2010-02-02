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
    $cells=explode("||", $r);
    if($cells[0]=="")
      array_splice($cells, 0, 1);
    elseif(ereg("^\|.*\|(.*)$", $r, $m))
      $cells[0]=$m[1];

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

function parse_key($key, $list_columns) {
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
  else {
    $l1=explode("=", $key);
    if(sizeof($l1)==1)
      return null;
    if($l1[1]=="*") {
      $ret="\"$l1[0]\" is not null";
      if($i==0)
	$list_columns[$l1[0]][]="*";
    }
    else {
      $ret="\"$l1[0]\"='$l1[1]'";
      if($i==0)
	$list_columns[$l1[0]][]=$l1[1];
    }
  }

  return $ret;
}

function parse_wholekey($k, $list_columns) {
  $ret=array();

  $keys_list=explode(",", $k);
  foreach($keys_list as $keys_part) {
    $keys=explode(" ", $keys_part);

    $l=array();
    foreach($keys as $i=>$k) {
      $l1=parse_key($k, &$list_columns);
      if($l1) $l[]=$l1;
    }

    $ret[]=implode(" AND ", $l);
  }

  if(sizeof($ret)>1)
    return "(".implode(") OR (", $ret).")";
  else
    return $ret[0];
}
