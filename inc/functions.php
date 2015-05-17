<?php
    // Source: http://www.php.net/manual/en/ref.array.php#81081
    // Thanks to dennis at DONTSPAMME dot born05 dot nl
    /**
     * make a recursive copy of an array
     *
     * @param array $aSource
     * @return array    copy of source array
     */
    function array_copy ($aSource) {
        // check if input is really an array
        if (!is_array($aSource)) {
            throw new Exception("Input is not an Array");
        }
       
        // initialize return array
        $aRetAr = array();
       
        // get array keys
        $aKeys = array_keys($aSource);
        // get array values
        $aVals = array_values($aSource);
       
        // loop through array and assign keys+values to new return array
        for ($x=0;$x<count($aKeys);$x++) {
            // clone if object
            if (is_object($aVals[$x])) {
                $aRetAr[$aKeys[$x]]=clone $aVals[$x];
            // recursively add array
            } elseif (is_array($aVals[$x])) {
                $aRetAr[$aKeys[$x]]=array_copy ($aVals[$x]);
            // assign just a plain scalar value
            } else {
                $aRetAr[$aKeys[$x]]=$aVals[$x];
            }
        }
       
        return $aRetAr;
    }

function array_deep_copy (&$array, &$copy, $maxdepth=50, $depth=0) {
    if($depth > $maxdepth) { $copy = $array; return; }
    if(!is_array($copy)) $copy = array();
    foreach($array as $k => &$v) {
        if(is_array($v)) {        array_deep_copy($v,$copy[$k],$maxdepth,++$depth);
        } else {
            $copy[$k] = $v;
        }
    }
}


function split_semicolon($str) {
  $x=explode(";", $str);
  $ret=array();

  for($i=0; $i<sizeof($x); $i++) {
    if(substr($x[$i], 0, 1)=="\"") {
      $j=$i;
      while(substr($x[$j], -1)!="\"")
	$j++;
      $y=implode(";", array_slice($x, $i, $j-$i+1));
      $ret[]=substr($y, 1, -1);
      $i=$j;
    }
    else
      $ret[]=$x[$i];
  }

  return $ret;
}

// Source: http://netevil.org/blog/2006/nov/http-post-from-php-without-curl
// Access: 2010-03-31
function do_post_request($url, $data, $optional_headers = null)
{
   $params = array('http' => array(
	        'method' => 'POST',
	        'content' => $data
	     ));
   if ($optional_headers !== null) {
      $params['http']['header'] = $optional_headers;
   }
   $ctx = stream_context_create($params);
   $fp = @fopen($url, 'rb', false, $ctx);
   if (!$fp) {
      throw new Exception("Problem with $url, $php_errormsg");
   }
   $response = @stream_get_contents($fp);
   if ($response === false) {
      throw new Exception("Problem reading data from $url, $php_errormsg");
   }
   return $response;
}

// returns the first non-null value
function coalesce() {
  $args=func_get_args();

  for($i=0; $i<sizeof($args); $i++) {
    if($args[$i])
      return $args[$i];
  }
}

function shell_escape($str) {
  return '"' . strtr($str, array('"' => '\\"')) . '"';
}

function id_escape($str) {
  $ret = "";

  for($i = 0; $i < strlen($str); $i++) {
    $c = substr($str, $i, 1);

    if(preg_match("/^[0-9a-zA-Z@]$/", $c))
      $ret .= $c;
    elseif($c == "_")
      $ret .= "__";
    elseif($c == "/")
      $ret .= "_";
  }

  return $ret;
}

function json_readable_encode($data) {
  if(version_compare(PHP_VERSION, '5.4.0') >= 0)
    return json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  else
    return json_encode($data);
}
