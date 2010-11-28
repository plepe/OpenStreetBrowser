<?
function json_split($text) {
  $ret=array();

  $i=0;
  while($i+1024<strlen($text)) {
    $anz=1024;
    
    if(($a=strrpos(substr($text, $i, $anz), "\n"))!==false) {
      $anz=$a;
    }

    while(eregi("&[a-zA-Z0-9]*$", substr($text, $i, $anz))) {
      $anz--;
    }

    $ret[]=substr($text, $i, $anz);

    $i+=$anz;
  }

  $ret[]=substr($text, $i);

  return $ret;
}

