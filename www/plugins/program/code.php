<?
function program_parse($str, &$pos=0, $rek=0) {
  $ret=array();
  $params=array();
  $fun="";
  $text="";
  $mode=0;
  $param_nr=0;

  while($pos<strlen($str)) {
    $chr=substr($str, $pos, 1);
    print "READ $chr ($rek, mode $mode)\n";

    switch($mode) {
      case 0:
	switch($chr) {
	  case "[":
	    if($text!="") {
	      $ret[]=$text;
	      $text="";
	    }
	    $mode=1;
	    $param_start=$pos+1;
	    $param_content="";
	    break;
	  case "]":
	    print "Error parsing program, character $pos (read \"$chr\", mode $mode, rek $rek)\n";
	    return;
	  case ",":
	  case ")":
	    if($rek) {
	      if($text!="") {
		$ret[]=$text;
		$text="";
	      }
	      return $ret;
	    }
	    else
	      $text.=$chr;
	    break;
	  default:
	    $text.=$chr;
	    // print "Error parsing character $pos (mode $mode)\n";
	    // return null;
	}
        break;
      case 1:
        switch($chr) {
	  case "]":
	  case ",":
	  case ")":
	    print "Error parsing program, character $pos (read \"$chr\", mode $mode, rek $rek)\n";
	    return;
	  default:
	    $text.=$chr;
	    $mode=2;
	}
	break;
      case 2:
        switch($chr) {
	  case "]":
	    $ret[]=array("fun"=>"tag_get", $text);
	    $text="";
	    $mode=0;
	    break;
	  case "(":
	    $params['fun']=$text;
	    $param_name="";
	    $text="";
	    $mode=3;
	    break;
	  default:
	    $text.=$chr;
	    //print "Error parsing program, character $pos (read \"$chr\", mode $mode, rek $rek)\n";
	    //return;
	}
	break;
      case 3:
	$param_start=$pos;
	if($chr==")")
	  $mode=6;
	elseif(preg_match("/^[a-zA-Z]$/", $chr)) {
	  $mode=4;
	  $text.=$chr;
	}
	else
	  $mode=5;
	break;
      case 4:
	if($chr==":") {
	  $param_name=$text;
	  $param_start=$pos+1;
	  $text="";
	  $mode=5;
	}
	else if(preg_match("/^[a-zA-Z]$/", $chr)) {
	  $text.=$chr;
	}
	else {
	  $mode=5;
	}
	break;
      case 5:
	if($param_name=="") {
	  $param_name=$param_nr++;
	}
	$pos=$param_start;
        $p=program_parse($str, $pos, $rek+1);
	if(!$p)
	  return;
        $params[$param_name]=$p;
	$param_name="";
	$pos--;
	$text="";
	$mode=6;
	break;
      case 6:
        switch($chr) {
	  case ")":
	    $ret[]=$params;
	    $params=array();
	    $mode=7;
	    break;
	  case ",":
	    $mode=3;
	    break;
	  default:
	    print "Error parsing program, character $pos (read \"$chr\", mode $mode, rek $rek)\n";
	    return;
	}
	break;
      case 7:
        switch($chr) {
	  case "]":
	    $mode=0;
	    break;
	  default:
	    print "Error parsing program, character $pos (read \"$chr\", mode $mode, rek $rek)\n";
	    return;
	}
	break;
      default:
	print "Error parsing program, character $pos (read \"$chr\", mode $mode, rek $rek)\n";
	return;
    }

    $pos++;
  }

  if($text!="") {
    $ret[]=$text;
  }

  return $ret;
}
