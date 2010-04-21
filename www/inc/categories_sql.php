<?
function build_sql_match_table($rules, $table="point", $id="tmp", $importance) {
  global $postgis_tables;
  $classify_function="";
  $tag_list=array();
  $table_def=$postgis_tables[$table];
  $add_columns=array();

  $match_list=$rules['match'];
  $select=array();
  $where=array();

  $select[]="{$table_def[sql_id_type]} as osm_type";
  $select[]="{$table_def[sql_id_name]} as osm_id";
  $select[]="{$table_def[geo]} as geo";
  
  $i=0;
  foreach($match_list as $i=>$match) {
    $rule_id=$rules['rule_id'][$i];
    $part=match_collect_values_part($match);

    foreach($part as $key=>$values) {
      if(!in_array($key, $add_columns)) {
	$classify_function_declare.="  tag_{$i} text[];\n";
	$classify_function_getdata.="  tag_{$i}:=split_semicolon(tags_get(osm_type, osm_id, ".postgre_escape($key)."));\n";
	$add_columns[]=$key;
	$tag_list[$key]=$i;

	$i++;
      }
    }

    $qry=match_to_sql($match, $tag_list, "columns");

    $classify_function_match.="  if $qry then return $rule_id; end if;\n";
  }

  $values=match_collect_values($match_list);
  //$where.=match_to_sql(, $table_def, "index");

  $w=array();
  $c_fix=array();
  $c_add=array();
  foreach($values as $key=>$values) {
    $values_escape=array();
    foreach($values as $v)
      $values_escape[]=postgre_escape($v);
    
    if(in_array($key, $table_def[index])) {
      $w[]="(to_tsvector('simple', planet_osm_$table.$key) @@ to_tsquery('simple', ".
	   implode("||' | '||", $values_escape)."))";
      $c_fix[]=postgre_escape($key);
    }
    else {
      $w[]="(speedup.k=".postgre_escape($key)." and to_tsvector('simple', speedup.v) @@ to_tsquery('simple', ".
	   implode("||' | '||", $values_escape)."))";
      $c_add[]=postgre_escape($key);
    }
  }
  $where[]=implode(" or ", $w);

  $rule_select.="END) as rule_id\n";

  $from="from planet_osm_$table\n";
  if(sizeof($c_add)) {
    $from.="  join {$table_def[id_type]}_tags speedup on speedup.{$table_def[id_type]}_id=planet_osm_$table.osm_id and speedup.k in (".implode(", ", array_merge($c_fix, $c_add)).")\n";
  }
    
  $funname="classify_{$id}_{$table}_{$importance}";
  $classify_function.="create or replace function $funname(text, int)\n";
  $classify_function.="returns int as $$\n";
  $classify_function.="declare\n";
  $classify_function.="  osm_type alias for $1;\n";
  $classify_function.="  osm_id   alias for $2;\n";
  $classify_function.=$classify_function_declare;
  $classify_function.="begin\n";
  $classify_function.=$classify_function_getdata;
  $classify_function.=$classify_function_match;
  $classify_function.="  return null;\n";
  $classify_function.="end;\n";
  $classify_function.="$$ language plpgsql;\n";
  sql_query($classify_function);
  $f=fopen("/tmp/functions.lst", "a");
  fwrite($f, $classify_function);
  fclose($f);

  $select[]="$funname({$table_def[sql_id_type]}, {$table_def[sql_id_name]}) as rule_id";

  $where=implode(" and\n  ", $where);
  $select=implode(", ", $select);
  return "select t2.*, cd.display_name_pattern, cd.display_type_pattern from (select {$select} {$from} where\n  {$where}) as t2 join categories_def cd on cd.category_id='$id' and cd.rule_id=t2.rule_id";
}

// Parses a matching string as used in categories
function parse_match($match, $table="point") {
  $match_parts=parse_explode($match);

  if(is_string($match_parts)) {
    error("Error: $match_parts");
    return array("case"=>array());
  }

  $or_parts=array("or");
  $parts=array("and");

  foreach($match_parts as $part) {
    if($part=="OR") {
      if(sizeof($parts)==2)
	$or_parts[]=$parts[1];
      else
	$or_parts[]=$parts;
      $parts=array("and");
    }
    else {
      $b=build_match_part($part, $table);

      if(is_string($b))
	error("Error: $b");
      else
	$parts[]=$b;
    }
  }

  if(sizeof($parts)==2)
    $or_parts[]=$parts[1];
  else
    $or_parts[]=$parts;

  if(sizeof($or_parts)==2)
    return $or_parts[1];

  return $or_parts;
}

function postgre_escape($str) {
  return "E'".strtr($str, array("'"=>"\\'"))."'";
}

function match_to_sql_colname($col, $table_def, $type="columns") {
  return "tag_{$table_def[$col]}";
}

function match_to_sql($match, $table_def) {
  $not="";
  $same="false";

  switch($match[0]) {
    case "or":
      if(sizeof($match)==1)
	return "true";

      $ret=array();
      for($i=1; $i<sizeof($match); $i++) {
	$ret[]=match_to_sql($match[$i], $table_def);
      }

      return "(".implode(") or (", $ret).")";
    case "and":
      if(sizeof($match)==1)
	return "true";

      $ret=array();
      for($i=1; $i<sizeof($match); $i++) {
	$ret[]=match_to_sql($match[$i], $table_def);
      }

      return "(".implode(") and (", $ret).")";
    case "not":
      return "not ".match_to_sql($match[1], $table_def);
    case "fuzzy is":
      $ret=array();
      for($i=2; $i<sizeof($match); $i++) {
	$ret[]=postgre_escape($match[$i]);
      }

      return "to_tsvector('simple', ".match_to_sql_colname($match[1], $table_def, "index").") @@ to_tsquery('simple', ".implode("||' | '||", $ret).")";
    case "is not":
      $not="not";
    case "is":
      $ret=array();
      for($i=2; $i<sizeof($match); $i++) {
	$ret[]=postgre_escape($match[$i]);
      }

      return "$not oneof_in(".match_to_sql_colname($match[1], $table_def).", ARRAY[".implode(", ", $ret)."])";
    case "exist":
      return match_to_sql_colname($match[1], $table_def)." is not null";
    case "exist not":
      return match_to_sql_colname($match[1], $table_def)." is null";
    case ">=":
      $same="true";
    case ">":
      return "oneof_between(".match_to_sql_colname($match[1], $table_def).", ".parse_number($match[2]).", $same, null, null)";
    case "<=":
      $same="true";
    case "<":
      return "oneof_between(".match_to_sql_colname($match[1], $table_def).", null, null, ".parse_number($match[2]).", $same)";
    case "true":
      return "true";
    case "false":
      return "false";
    default:
      return "X";
  }
}

function match_collect_values_part($el) {
  $ret=array();

  switch($el[0]) {
    case "fuzzy is":
    case "is":
      for($i=2; $i<sizeof($el); $i++)
	$ret[$el[1]][]=$el[$i];
      break;
    case "exist":
    case "is not":
    case ">":
    case "<":
    case ">=":
    case "<=":
      $ret[$el[1]][]=true;
      break;
    case "exist not":
      $ret[$el[1]][]=false;
      break;
    case "and":
    case "or":
      for($i=1; $i<sizeof($el); $i++)
	$ret=array_merge_recursive($ret, match_collect_values_part($el[$i]));
  }

  return $ret;
}

function match_collect_values($arr) {
  $vals=array();
  $ret=array("or");

  foreach($arr as $el) {
    $vals=array_merge_recursive($vals, match_collect_values_part($el));
  }

  foreach($vals as $key=>$values) {
    $vals[$key]=array_unique($values);
  }

  return $vals;
/*
  foreach($vals as $key=>$values) {
    if(in_array(true, $values, true)&&in_array(false, $values, true)) {
      $ret[]=array("true");
    }
    elseif(in_array(true, $values, true)) {
      $ret[]=array("exist", $key);
    }
    else {
      $x=array("fuzzy is", $key);
      foreach($values as $v)
        if($v!==false)
	  $x[]=$v;

      if((sizeof($values)>1)&&in_array(false, $values, true))
	$ret[]=array("or", array("exist not", $key), $x);
      else
	$ret[]=$x;
    }
  }

  return $ret; */
}

function build_match_part($part) {
  $c_not=null;
  $where=array();
  $case=array();

  for($i=0; $i<sizeof($part['operators']); $i++) {
    $operator=$part['operators'][$i];
    $values=$part['values'][$i];

    $c=array();
    $c_prevnot=$c_not;
    $c_not=false;
    $where_not="";
    switch($operator) {
      case "!=":
        $c_not=true;
	$where_not="!";
      case "=":
	$c=array(
	  ($c_not?"is not":"is"),
	  $part['key'],
	);
	$c1=array();
	$ccount=0;
	foreach($values as $v) {
	  if($v!="*") {
	    $c[]=$v;
	    $ccount++;
	  }
	}

	foreach($values as $v) {
	  if(($v=="*")&&($c_not==false)) {
	    $c[0]="exist";
	  }
	  elseif(($v=="*")&&($c_not==true)) {
	    $c[0]="exist not";
	  }
	}
	
	if($c_prevnot===true) {
	  $case=array("or", $case, $c);
	}
	elseif($c_prevnot===false) {
	  $case=array("and", $case, $c);
	}
	else
	  $case=$c;
	break;
      case ">":
      case "<":
      case ">=":
      case "<=":
        if(sizeof($values)>1)
	  print "Operator $operator , more than one value supplied\n";
	$c_not=false;

	$c=array(
	  $operator,
	  $part['key'],
	);

	$c[]=$values[0];

	if($c_prevnot===true) {
	  $case=array("or", $case, $c);
	}
	elseif($c_prevnot===false) {
	  $case=array("and", $case, $c);
	}
	else
	  $case=$c;

        break;
    }
    // where-statement

    //print_r($c);
  }

  return $case;
}

function parse_explode($match) {
  $i=0;
  $m=0;

  $key="";
  $operators=array();
  $operator="";
  $values=array();
  unset($value);

  for($i=0; $i<strlen($match); $i++) {
    $c=substr($match, $i, 1);

    switch($m) {
      case 0:
	if(in_array($c, array("=", "!", ">", "<"))) {
	  $m=1;
	  $i--;
	}
	elseif($c==",") {
	  $parser[]="OR";
	}
	elseif($c==" ") {
	}
	elseif(!in_array($c, array("\"", "'"))) {
	  $key.=$c;
	}
	else {
	  return "Error parsing match string: \"$match\"!";
	}
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
	  unset($value);
	}
	elseif(in_array($c, array("=", "!", ">", "<"))) {
	  $values[sizeof($values)-1][]=$value;
	  unset($value);
	  $m=1;
	  $i--;
	}
	elseif(($c==" ")||($c==",")) {
	  $values[sizeof($values)-1][]=$value;
	  $parser[]=array("key"      =>$key,
	                  "operators"=>$operators,
			  "values"   =>$values);

	  if($c==",") {
	    $parser[]="OR";
	  }

	  $key="";
	  $operator="";
	  $operators=array();
	  $values=array();
	  unset($value);

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

  if(isset($value))
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

function category_build_where($where_col, $where_vals) {
  $ret=array();

  $where_vals=array_unique($where_vals);
  if(in_array("null", $where_vals, "null")&&(in_array("!null", $where_vals))) {
    // nix
  }
  elseif(in_array("!null", $where_vals)) {
    $vals=array();
    foreach($where_vals as $v)
      if(($v!="!null")&&(substr($v, 0, 1)=="!"))
	$vals[]=$v;

    $r="$where_col is not null";
    if(sizeof($vals))
      $r="($r and not to_tsvector($where_col) @@ ".
          "to_tsquery(".implode("||'|'||", $vals)."))";
    $ret[]=$r;
  }
  else {
    $in_vals=array();
    $notin_vals=array();
    foreach($where_vals as $val) {
      if($val=="null");
      elseif(substr($val, 0, 1)=="!")
	$notin_vals[]=substr($val, 1);
      else
	$in_vals[]=$val;
    }

    if(sizeof($in_vals))
      $ret[]="to_tsvector('simple', $where_col) @@ to_tsquery('simple', ".implode("||'|'||", $in_vals).")";
  }

  return $ret;
}
