<?
function parse_css($str) {
  $arr=split_semicolon($str);
  $ret=array();

  foreach($arr as $a) {
    if(preg_match("/^\s*([A-Za-z\-_]*)\s*:(.*)/", $a, $m)) {
      $ret[$m[1]]=trim($m[2]);
    }
  }

  return $ret;
}

class css {
  function __construct($base_style="") {
    $this->style=parse_css($base_style);
  }

  function apply($str) {
    $this->style=array_merge($this->style, parse_css($str));
    return $this->style;
  }

  function dom_set_attributes($dom) {
    foreach($this->style as $key=>$value) {
      $dom->setAttribute($key, $value);
    }
  }
}
