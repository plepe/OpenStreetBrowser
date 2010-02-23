<?
// Parses a matching string as used in categories
function parse_match($match, $table="point") {
  $match_parts=parse_explode($match);
  $parts=array();

  foreach($match_parts as $part) {
    $parts[]=build_match_part($part, $table);
  }

  $ret=array();
  foreach($parts as $def) {
    foreach($def as $part=>$text) {
      if($text)
	$ret[$part][]=$text;
    }
  }

  $where=array();
  foreach($ret['where'] as $w) {
    foreach($w as $k=>$vs) {
      $where[$k]=$vs;
    }
  }
  $ret['where']=$where;

  if(sizeof($ret['case']))
    $ret['case']=implode(" and ", $ret['case']);
  else
    $ret['case']="1=1";

  return $ret;
}

function postgre_escape($str) {
  return "E'".strtr($str, array("'"=>"\\'"))."'";
}

function build_match_part($part, $table) {
  $c_not=null;
  global $postgis_tables;
  $table_def=$postgis_tables[$table];
  $where=array();

  for($i=0; $i<sizeof($part['operators']); $i++) {
    $operator=$part['operators'][$i];
    $values=$part['values'][$i];

    $col_name="\"$part[key]\"";
    if(!in_array($part['key'], $table_def[index])) {
      $col_name="\"$part[key]_table\".v";
    }

    // case-statement
    $c_prevnot=$c_not;
    $c_not=false;
    $where_not="";
    switch($operator) {
      case "!=":
        $c_not=true;
	$where_not="!";
      case "=":
	$c=$col_name;
	$c1="";
	$ccount=0;
        $c1.=($c_not?" not":"")." in (";
	$c2=array();
	foreach($values as $v) {
	  if($v!="*") {
	    $c2[]=postgre_escape($v);
	    $ccount++;
	    $where[$col_name][]=$where_not.postgre_escape($v);
	  }
	}
	$c1.=implode(", ", $c2).")";

	if($ccount>0) {
	  $c.=$c1;
	}

	foreach($values as $v) {
	  if(($v=="*")&&($c_not==false)) {
	    $c.=" is not null";
	    $where[$col_name][]="!null";
	  }
	  elseif(($v=="*")&&($c_not==true)) {
	    $c.=" is null";
	    $where[$col_name][]="null";
	  }
	}
	
	if($c_prevnot===true) {
          $case.=" and ";
	}
	elseif($c_prevnot===false) {
          $case.=" or ";
	}
	$case.=$c;
	break;
      case ">":
      case "<":
      case ">=":
      case "<=":
        if(sizeof($values)>1)
	  print "Operator $operator , more than one value supplied\n";
	$c_not=true;
	$where[$col_name][]="!null";

	if($c_prevnot===true) {
	  $case.=" and ";
	}
	elseif($c_prevnot===false) {
	  $case.=" or ";
	}

	$c.="parse_number($col_name)";
	$c.="{$operator}parse_number(".postgre_escape($values[0]).")";

	$case.=$c;

        break;
    }
    $c="";
    // where-statement
  }

  // join-statement
  $join="";
  $select="\"{$part['key']}\"";
  if(!in_array($part['key'], $table_def[index])) {
    $join.="left join {$table_def[id_type]}_tags \"{$part[key]}_table\" on planet_osm_{$table}.osm_id=\"{$part[key]}_table\".{$table_def[id_type]}_id and \"{$part[key]}_table\".k='$part[key]'";
    $select="\"{$part[key]}_table\".v as \"{$part['key']}\"";
  }

  $case="($case)";

  return array("case"=>$case, "where"=>$where, "join"=>$join, "columns"=>$part['key'], "select"=>$select);
}

function parse_explode($match) {
  $i=0;
  $m=0;

  $key="";
  $operators=array();
  $operator="";
  $values=array();
  $value="";

  for($i=0; $i<strlen($match); $i++) {
    $c=substr($match, $i, 1);

    switch($m) {
      case 0:
	if(in_array($c, array("=", "!", ">", "<"))) {
	  $m=1;
	  $i--;
	}
	else
	  $key.=$c;
	break;
      case 1:
	if(in_array($c, array("=", "!", ">", "<"))) {
	  $operator.=$c;
	}
	else {
	  $operators[]=$operator;
	  $operator="";
	  $values[]=array();
	  $m=2;
	  $i--;
	}
        break;
      case 2:
        if($c=="\"") {
	  $m=3;
	}
	elseif($c==";") {
	  $values[sizeof($values)-1][]=$value;
	  $value="";
	}
	elseif(in_array($c, array("=", "!", ">", "<"))) {
	  $values[sizeof($values)-1][]=$value;
	  $value="";
	  $m=1;
	  $i--;
	}
	elseif($c==" ") {
	  $values[sizeof($values)-1][]=$value;
	  $parser[]=array("key"      =>$key,
	                  "operators"=>$operators,
			  "values"   =>$values);

	  $key="";
	  $operator="";
	  $operators=array();
	  $values=array();
	  $value="";

	  $m=0;
	}
	elseif($c=="\\") {
	  $i++;
	  $value.=substr($match, $i, 1);
	}
	else
	  $value.=$c;
	break;
      case 3:
	if($c=="\"") {
	  $m=2;
	}
	elseif($c=="\\") {
	  $i++;
	  $value.=substr($match, $i, 1);
	}
	else
	  $value.=$c;
      default:
	break;
    }
  }

  $values[sizeof($operators)-1][]=$value;
  $parser[]=array("key"      =>$key,
		  "operators"=>$operators,
		  "values"   =>$values);

  return $parser;
}

function parse_kind($kind, $table) {
  global $postgis_tables;
  $table_def=$postgis_tables[$table];
  $parts=array();

  foreach($kind as $k) {
    $join="";
    $select="";
    if(!in_array($k, $table_def[columns])) {
      $join.="left join {$table_def[id_type]}_tags \"{$k}_table\" on planet_osm_{$table}.osm_id=\"{$k}_table\".{$table_def[id_type]}_id and \"{$k}_table\".k='$k'";
      $select="\"{$k}_table\".v as \"{$k}\"";
    }

    $parts[]=array("columns"=>$k,
		   "join"=>$join,
		   "select"=>$select);
  }

  $ret=array();
  foreach($parts as $def) {
    foreach($def as $part=>$text) {
      if($text)
	$ret[$part][]=$text;
    }
  }

  return $ret;
}
