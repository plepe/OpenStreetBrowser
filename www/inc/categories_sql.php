<?
$maybe_delete_indexes=array();
$default_tags=array(
  'point'=>array(
    'icon_text'=>"[name];[ref];[operator]",
  ),
  'line'=>array(
    'line_text'=>"[name];[ref];[operator]",
  ),
  'polygon'=>array(
    'icon_text'=>"[name];[ref];[operator]",
  ),
);

function register_index($table, $key, $type, $id, $val=null) {
  $key=postgre_escape($key);
  $val=postgre_escape($val);
  print "register index called: {$id} {$table} {$type} {$key} {$val}\n";

  $res=sql_query("select * from indexes where _table='$table' and _key=$key and _type='$type' and _val=$val");
  if(!pg_num_rows($res)) {
    switch($type) {
      case "tsvector":
        sql_query("create index \"osm_{$table}_{$type}_{$key}\" on osm_{$table} using gist(osm_way, to_tsvector('simple', osm_tags->$key))");
	break;
      case "highest_number":
        //sql_query("create index \"osm_{$table}_{$type}_{$key}\" on osm_{$table} using gist(osm_way, parse_highest_number(osm_tags->$key))");
	break;
      case "gteq":
        //sql_query("create index \"osm_{$table}_{$type}_{$key}_{$val}\" on osm_{$table} using gist(osm_way, osm_tags) where oneof_between(split_semicolon(osm_tags->$key), $val, true, null, null)");
	break;
      case "lteq":
        //sql_query("create index \"osm_{$table}_{$type}_{$key}_{$val}\" on osm_{$table} using gist(osm_way, osm_tags) where oneof_between(split_semicolon(osm_tags->$key), null, null, $val, true)");
	break;
    }
  }

  sql_query("insert into indexes values ('$table', $key, '$type', $val, '$id')");
}

function tmp_delete_indexes($id) {
  global $maybe_delete_indexes;

  $res=sql_query("select *, (select count(*) from indexes it where i._table=it._table and i._key=it._key and i._type=it._type and id!='$id') as count from indexes i where id='$id'");
  while($elem=pg_fetch_assoc($res)) {
    if($elem[count]==0)
      $maybe_delete_indexes[]=$elem;
  }

  print_r($maybe_delete_indexes);

  sql_query("delete from indexes where id='$id'");
}

function delete_indexes($id) {
  global $maybe_delete_indexes;

  $res=sql_query("select * from indexes where id='$id'");
  while($elem=pg_fetch_assoc($res)) {
    $list[]=$elem;
  }

  print_r($list);

  for($i=0; $i<sizeof($maybe_delete_indexes); $i++) {
    $found=false;
    for($j=0; $j<sizeof($list); $j++) {
      if(($list['table']==$maybe_delete_indexes['table'])&&
         ($list['key']==$maybe_delete_indexes['key'])&&
         ($list['type']==$maybe_delete_indexes['type']))
	$found=true;
    }

    if(!$found) {
      sql_query("drop index \"osm_{$maybe_delete_indexes['table']}_{$maybe_delete_indexes['type']}_{$maybe_delete_indexes['key']}\"");
    }
  }
}

function build_sql_match_table($rules, $table="point", $id="tmp", $importance) {
  $tag_list=array();
  $add_columns=array();

  if(!sizeof($rules))
    return null;

  $match_list=$rules['match'];
  $select=array();
  $where=array();

  $select[]="osm_id";
  $select[]="osm_way as geo";
  $select[]="osm_tags";
  
  $or_list=array("or");
  $i=0;
  foreach($match_list as $i=>$match) {
    $or_list[]=$match;
  }

  $w=match_to_sql(match_simplify($or_list), array("table"=>$table, "id"=>$id), "index");
  $where[]="($w)";

  $from="from osm_$table\n";

  $funname="classify_{$id}_{$table}";

  $select[]="$funname(osm_id, osm_tags, osm_way) as result";

  //$where[]="(\"rule_$id\"='$importance' or \"rule_$id\" is null)";

  if(in_array($importance, array("global", "international", "national")))
    $where[]="Intersects(osm_way, !bbox!)";
  else
    $where[]="osm_way&&!bbox!";

  print "WHERE";
  print_r($where);

  if(sizeof($where))
    $where="where\n  ".implode(" and\n  ", $where);
  else
    $where="";

  $select=implode(", ", $select);
  return "select t2.osm_id as osm_id, t2.geo, t2.osm_tags as osm_tags, t2.result->'rule_id' as rule_id, t2.result->'importance' as importance, result as rule_tags from (select {$select} {$from} {$where}) as t2 where t2.result->'importance'='$importance'";// group by t2.result[1], t2.result[2], t2.result[3]";
  //return "select array_to_string(to_textarray(t2.osm_id), ';') as osm_id, ST_Collect(t2.geo) as geo, tags_merge(to_array(t2.osm_tags)) as osm_tags, t2.result[1] as rule_id, t2.result[2] as importance, tags_merge(to_array(cd.rule_tags)) as rule_tags from (select {$select} {$from} {$where}) as t2 join categories_def cd on cd.category_id='$id' and cd.rule_id=t2.result[1] and t2.result[2]='$importance' group by t2.result[1], t2.result[2], t2.result[3]";
//select *, rule_tags->'display_name_pattern' as display_name_pattern, rule_tags->'display_type_pattern' as display_type_pattern, rule_tags->'icon_text_pattern' as icon_text_pattern from (
}

function create_sql_classify_fun($rules, $table="point", $id="tmp") {
  global $postgis_tables;
  global $default_tags;

  $def_tags=$default_tags[$table];
  $classify_function_declare="";
  $classify_function_getdata="";
  $classify_function="";
  $tag_list=array();
  $table_def=$postgis_tables[$table];
  $add_columns=array();

  $match_list=$rules['match'];
  $select=array();
  $where=array();

  $i=0;
  foreach($match_list as $i=>$match) {
    $rule_id=$rules['rule_id'][$i];
    $tags=$rules['rule'][$i];
    $part=match_collect_values_part($match);

    foreach($part as $key=>$values) {
      if(!in_array($key, $add_columns)) {
	$classify_function_declare.="  tag_{$i} text[];\n";
	$classify_function_getdata.="  tag_{$i}:=split_semicolon(osm_tags->".postgre_escape($key).");\n";
	$add_columns[]=$key;
	$tag_list[$key]=$i;

	$i++;
      }
    }

    $qry=match_to_sql($match, $tag_list, "exact");

    $imp=$tags->get("importance");
    if(!$imp)
      $tags->set("importance", "local");
//    elseif(strpos($imp, "[")) {
//      $imp=postgre_escape($imp);
//      $imp="tags_parse(_osm_id, osm_tags, osm_way, $imp)";
//    }
//    else {
//      $imp=postgre_escape($imp);
//    }

    if($x=$tags->get("group")) {
      $x=postgre_escape($x);
      $group_id="tags_parse(_osm_id, osm_tags, osm_way, $x)";
    }
    else {
      $group_id="_osm_id";
    }

    $arr=$tags->data();
    $arr['rule_id']=$rule_id;
    $classify_function_match[]="if $qry then\n".
      "    result:=".array_to_hstore(array_merge($def_tags, $arr)).";";
  }

  $classify_function_declare.="  result hstore;\n";
  $classify_function_match=implode("\n  else", $classify_function_match);
  if($classify_function_match)
    $classify_function_match="  $classify_function_match\n  end if;\n";
  $classify_function_match.="\n";
//  $classify_function_match.="  if result is not null then\n";
//  $classify_function_match.="    update planet_osm_$table set \"rule_$id\"=result[2] where planet_osm_$table.osm_id=_osm_id and \"rule_$id\" is null;\n";
//  $classify_function_match.="  else\n";
//  $classify_function_match.="    update planet_osm_$table set \"rule_$id\"='' where planet_osm_$table.osm_id=_osm_id and \"rule_$id\" is null;\n";
//  $classify_function_match.="  end if;\n";

  $funname="classify_{$id}_{$table}";
  $classify_function.="create or replace function $funname(text, hstore, geometry)\n";
  $classify_function.="returns hstore as $$\n";
  $classify_function.="declare\n";
  $classify_function.="  _osm_id   alias for $1;\n";
  $classify_function.="  osm_tags  alias for $2;\n";
  $classify_function.="  osm_way   alias for $3;\n";
  $classify_function.=$classify_function_declare;
  $classify_function.="begin\n";
  $classify_function.=$classify_function_getdata;
  $classify_function.=$classify_function_match;
  $classify_function.="  if result is not null then\n";
  $classify_function.="    result:=result || ('importance'=>tags_parse(_osm_id, osm_tags, osm_way, result->'importance'))::hstore;\n";
  $classify_function.="  end if;\n";
  $classify_function.="  return result;\n";
  $classify_function.="end;\n";
  $classify_function.="$$ language plpgsql immutable;\n";

  return $classify_function;
}

// Parses a matching string as used in categories
function parse_match($match, $table="point") {
  if(!$match) {
    return "Error: match-string is empty!";
  }

  $match_parts=parse_explode($match);

  if(is_string($match_parts)) {
    return $match_parts;
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

function match_to_sql_colname($col, $table_def, $type="exact") {
  if($type=="exact")
    return "tag_{$table_def[$col]}";
  elseif($type=="index")
    return "osm_tags->".postgre_escape($col);
}

// valid types:
/// 'exact' ... Match via oneof_in and similar
/// 'index' ... Use tsvector or other types of indices
function match_to_sql($match, $table_def, $type="exact") {
  $not="";
  $same="false";

  switch($match[0]) {
    case "or":
      if(sizeof($match)==1)
	return "true";

      $ret=array();
      for($i=1; $i<sizeof($match); $i++) {
	$ret[]=match_to_sql($match[$i], $table_def, $type);
      }

      return "(".implode(") or (", $ret).")";
    case "and":
      if(sizeof($match)==1)
	return "true";

      $ret=array();
      for($i=1; $i<sizeof($match); $i++) {
	$ret[]=match_to_sql($match[$i], $table_def, $type);
      }

      return "(".implode(") and (", $ret).")";
    case "not":
      return "not ".match_to_sql($match[1], $table_def, $type);
    case "is not":
      $not="not";
    case "is":
      switch($type) {
	case "index":
	  $ret=array();
	  for($i=2; $i<sizeof($match); $i++) {
	    $ret[]="osm_tags @> ".array_to_hstore(array($match[1]=>$match[$i]));
	  }

	  return "$not (".implode(") or (", $ret).")";
	default:
	  $ret=array();
	  for($i=2; $i<sizeof($match); $i++) {
	    $ret[]=postgre_escape($match[$i]);
	  }

	  return "$not osm_tags->".postgre_escape($match[1])." in (".implode(", ", $ret).")";
	}
    case "~is not":
      $not="not";
    case "~is":
      switch($type) {
	case "index":
	  $ret=array();
	  for($i=2; $i<sizeof($match); $i++) {
	    $ret[]=postgre_escape($match[$i]);
	  }

	  register_index($table_def['table'], $match[1], "tsvector", 
	                 $table_def['id']);
	  return "$not to_tsvector('simple', ".match_to_sql_colname($match[1], $table_def, $type).") @@ to_tsquery('simple', ".implode("||' | '||", $ret).")";
	default:
	  $ret=array();
	  for($i=2; $i<sizeof($match); $i++) {
	    $ret[]=postgre_escape($match[$i]);
	  }

	  return "$not oneof_in(".match_to_sql_colname($match[1], $table_def, $type).", ARRAY[".implode(", ", $ret)."])";
	}
    case "exist":
      return "osm_tags ? ".postgre_escape($match[1]);
    case "exist not":
      return "not osm_tags ? ".postgre_escape($match[1]);
    case ">=":
      $same="true";
    case ">":
      $number=parse_number($match[2]);

      if($type=="index") {
	// for index-search we make an index every 100 
	// units and change the select-statement accordingly
	$same="true";
	$number=pow(100, floor(log($number, 100)+0.000001));
	register_index($table_def['table'], $match[1], "gteq", 
		       $table_def['id'], $number);
	$var="split_semicolon(".match_to_sql_colname($match[1], $table_def, $type).")";
      }
      else {
	$var=match_to_sql_colname($match[1], $table_def, $type);
      }

      return "oneof_between($var, $number, $same, null, null)";
    case "<=":
      $same="true";
    case "<":
      $number=parse_number($match[2]);

      if($type=="index") {
	$same="true";
	$number=pow(100, ceil(log($number, 100)));
	register_index($table_def['table'], $match[1], "lteq", 
		       $table_def['id'], $number);
	$var="split_semicolon(".match_to_sql_colname($match[1], $table_def, $type).")";
      }
      else {
	$var=match_to_sql_colname($match[1], $table_def, $type);
      }

      return "oneof_between($var, null, null, $number, $same)";
    case "true":
      return "true";
    case "false":
      return "false";
    default:
      print "invalid match! "; print_r($match);
      return "true";
  }
}

function match_collect_values_part($el) {
  $ret=array();

  switch($el[0]) {
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
      case "~!=":
      case "!=":
        $c_not=true;
	$where_not="!";
      case "~=":
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

	if(substr($operator, 0, 1)=="~") {
	  $c[0]="~{$c[0]}";
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
      case "~>":
      case "~<":
      case "~>=":
      case "~<=":
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
    //print "parse $m $i: \"$c\"\n";

    switch($m) {
      case 0:
        if($c=="\"") {
	  $m=4;
	}
	elseif($c==",") {
	  $parser[]="OR";
	}
        elseif($c==" ") {
	}
	elseif(!in_array($c, array("\"", "'", "=", "!", ">", "<", "~"))) {
	  $key.=$c;
	  $m=6;
	}
	else {
	  return "Syntax error parsing match string: \"$match\" at position $i!";
	}
	break;
      case 1:
	if(in_array($c, array("=", "!", ">", "<", "~"))) {
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
	elseif(in_array($c, array("=", "!", ">", "<", "~"))) {
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
	  if(!isset($value))
	    $value="";
	  $value.=substr($match, $i, 1);
	}
	else {
	  if(!isset($value))
	    $value="";
	  $value.=$c;
	}
	break;
      case 3:
	if($c=="\"") {
	  $m=2;
	}
	elseif($c=="\\") {
	  $i++;
	  if(!isset($value))
	    $value="";
	  $value.=substr($match, $i, 1);
	}
	else {
	  if(!isset($value))
	    $value="";
	  $value.=$c;
	}
	break;
      case 4:
	if($c=="\"") {
	  $m=5;
	}
	elseif($c=="\\") {
	  $i++;
	  if(!isset($value))
	    $value="";
	  $value.=substr($match, $i, 1);
	}
	else {
	  if(!isset($value))
	    $value="";
	  $value.=$c;
	}
	break;
      case 5:
	if(in_array($c, array("=", "!", ">", "<", "~"))) {
	  $m=1;
	  $i--;
	}
	else {
	  return "Syntax error parsing match string: \"$match\" at position $i!";
	}
	break;
      case 6:
	if(in_array($c, array("=", "!", ">", "<", "~"))) {
	  $m=1;
	  $i--;
	}
	elseif(!in_array($c, array("\"", "'", " ", ","))) {
	  $key.=$c;
	}
	else {
	  return "Syntax error parsing match string: \"$match\" at position $i!";
	}
	break;
      default:
	break;
    }
  }

  if($m==3) {
    return "Syntax error parsing match string: \"$match\": string not closed!";
  }

  if(isset($value))
    $values[sizeof($operators)-1][]=$value;
  $parser[]=array("key"      =>$key,
		  "operators"=>$operators,
		  "values"   =>$values);

  return $parser;
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
