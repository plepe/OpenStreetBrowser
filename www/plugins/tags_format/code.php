<?
global $tags_format_options_default;
$tags_format_options_default=array(
  "str_join"=>", ", "value_separator"=>": ", "count"=>1, 
);

/**
 * format tag(s) for display
 * @param string|array string(s) to translate
 *   variants:
 *   - instance of class tags
 *   - "k=v" or "k>=v"
 *   - array("k1=v1", "k2>=v2")
 *   - array("k1"=>"v1", "k2"=>"v2")
 *   - array("k"=>key, "v"=>value[, "op"=>operator])
 * @param hash options to configure display
 *   str_join: string which is used to join strings (default: ", ")
 *   value_separator: string which is used to join key and value (default: ": ")
 *   count: count of strings (for singular/plural) (default: 1)
 * @return string formatted tags
 */
function tags_format($data, $options=array()) {
  global $tags_format_options_default;
  $ret=array();

  // default values
  $options=array_merge($tags_format_options_default, $options);

  // build array in form array(array(k=>k1, v=>v1, op=>=), ...)
  $data=_tags_format_parse($data);

  // if array than iterate through str and join as string
  foreach($data as $s)
    $ret[]=_tags_format_single($s, $options);

  return implode($options['str_join'], $ret);
}

/**
 * format as single tag
 * @param array data
 *   variants:
 *   - array("k"=>"highway", "v"=>"motorway")
 *   - array("k"=>"population", "v"=>"1000", "op"=>">=")
 * @param array see tags_format()
 * @return string formated string
 */
function _tags_format_single($data, $options) {
  if($data['op']=="=")
    $data['op']="";

  $ret=lang($data['k'], $options['count']).
    $options['value_separator'].
    $data['op'].
    lang("{$data['k']}={$data['v']}", $options['count']);

  return $ret;
}

/**
 * parse data
 * @param string|array string(s) to translate
 *   variants:
 *   - instance of class tags
 *   - "k=v" or "k>=v"
 *   - array("k1=v1", "k2>=v2")
 *   - array("k1"=>"v1", "k2"=>"v2")
 *   - array("k"=>key, "v"=>value[, "op"=>operator])
 */
function _tags_format_parse($data) {
  $_data=array();

  if(is_string($data)) {
    // "highway=motorway" or "population>=1000"
    if(preg_match("/^([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/", $data, $m))
      $_data=array(array("k"=>$m[1], "v"=>$m[3], "op"=>$m[2]));
  }
  elseif(is_array($data)&&(sizeof($data)==2)&&
         isset($data['k'])&&isset($data['v'])) {
    // array("k"=>"highway", "v"=>"motorway")
    $_data=array_merge($data, array("op"=>"="));
  }
  elseif(is_array($data)&&(sizeof($data)==3)&&
         isset($data['k'])&&isset($data['v'])&&isset($data['op'])) {
    // array("k"=>"population", "v"=>"1000", "op"=>">=")
    $_data=$data;
  }
  elseif(is_a($data, "tags")) {
    // tags-instance
    $data=$data->data();
    foreach($data as $k=>$v) {
      $_data[]=array("k"=>$k, "v"=>$v, "op"=>"=");
    }
  }
  elseif(is_array($data)&&array_key_exists(0, $data)) {
    // array("highway=motorway", "population>=1000")
    foreach($data as $d) {
      if(preg_match("/^([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/", $d, $m))
        $_data[]=array("k"=>$m[1], "v"=>$m[3], "op"=>$m[2]);
    }
  }
  elseif(is_array($data)) {
    foreach($data as $k=>$v) {
      $_data[]=array("k"=>$k, "v"=>$v, "op"=>"=");
    }
  }
  else {
    print "tags_format::_tags_format_parse: Can't parse \$data\n";
  }

  return $_data;
}
