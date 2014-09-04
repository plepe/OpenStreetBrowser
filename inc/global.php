<?
function export_formated_text($key, $text) {
  $text=strtr($text, array(">"=>"&gt;", "<"=>"&lt;", "&"=>"&amp;"));

  $i=0;
  while($i+1024<strlen($text)) {
    $anz=1024;
    
    if(($a=strrpos(substr($text, $i, $anz), "\n"))!==false) {
      $anz=$a;
    }

    while(eregi("&[a-zA-Z0-9]*$", substr($text, $i, $anz))) {
      $anz--;
    }

    print "<$key>".substr($text, $i, $anz)."</$key>\n";
    $i+=$anz;
  }

  print "<$key>".substr($text, $i)."</$key>\n";
}

lang_init();
