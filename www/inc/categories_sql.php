<?
// Parses a matching string as used in categories
function parse_match($match) {
  $match_parts=parse_explode($match);
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
	  $parser[]=array($key, $operators, $values);

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
  $parser[]=array($key, $operators, $values);

  return $parser;
}

