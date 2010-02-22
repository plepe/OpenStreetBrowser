<?
// Parses a matching string as used in categories
function parse_match($match) {
  $match_parts=parse_explode($match);
  $parts=array();

  foreach($match_parts as $part) {
    $parts[]=build_match_part($part);
  }

  print_r($parts);
}

function postgre_escape($str) {
  return "E'".strtr($str, array("'"=>"\\'"))."'";
}

function build_match_part($part) {
  $c_not=null;

  for($i=0; $i<sizeof($part['operators']); $i++) {
    $operator=$part['operators'][$i];
    $values=$part['values'][$i];

    // case-statement
    $c_prevnot=$c_not;
    $c_not=false;
    switch($operator) {
      case "!=":
        $c_not=true;
      case "=":
	$c="\"$part[key]\"";
	$c1="";
	$ccount=0;
        $c1.=($c_not?" not":"")." in (";
	$c2=array();
	foreach($values as $v) {
	  if($v!="*") {
	    $c2[]=postgre_escape($v);
	    $ccount++;
	  }
	}
	$c1.=implode(", ", $c2).")";

	if($ccount>0) {
	  $c.=$c1;
	}

	foreach($values as $v) {
	  if($v=="*")
	    $c.=" is ".($c_not?"":"not ")."null";
	}
	
	if($c_prevnot===true) {
          $case.=" or ";
	}
	elseif($c_prevnot===false) {
          $case.=" and ";
	}
	$case.=$c;
	break;
      case ">":
      case "<":
      case ">=":
      case "<=":
        if(sizeof($values)>1)
	  print "Operator $operator , more than one value supplied\n";

	if($c_prevnot===true) {
	  $case.=" or ";
	}
	elseif($c_prevnot===false) {
	  $case.=" and ";
	}

	$c.="parse_number(\"$part[key]\")";
	$c.="{$operator}parse_number(".postgre_escape($values[0]).")";

	$case.=$c;

        break;
    }
    $c="";
    // where-statement
  }

  return array("case"=>$case, "where"=>$where);
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

