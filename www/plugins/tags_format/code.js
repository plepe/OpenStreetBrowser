var lang_tags_format_options_default={
  str_join: ", ", value_separator: ": ", count: 1
};

/**
 * format tag(s) for display
 * @param string|array string(s) to translate
 *   variants:
 *   - instance of class tags
 *   - "k=v" or "k>=v"
 *   - ["k1=v1", "k2>=v2"]
 *   - {"k1"=>"v1", "k2"=>"v2"}
 *   - {"k"=>key, "v"=>value[, "op"=>operator]}
* @param hash options to configure display
 *   str_join: string which is used to join strings (default: ", ")
 *   value_separator: string which is used to join key and value (default: ": ")
 *   count: count of strings (for singular/plural) (default: 1)
 * @return string formatted tags
 */
function tags_format(data, options) {
  var ret;

  // default values
  if(typeof options=="undefined")
    options={};
  options=array_merge(lang_tags_format_options_default, options);

  // build array in form array(array(k=>k1, v=>v1, op=>=), ...)
  data=_tags_format_parse(data);

  // if array than iterate through str and join as string
  ret=[];
  for(var i=0; i<data.length; i++)
    ret.push(_tags_format_single(data[i], options));

  return ret.join(options.str_join);
}

/**
 * format as single tag
 * @param array data
 * @param array see tags_format()
 * @return string formated string
 */
function _tags_format_single(data, options) {
  var ret;

  if(data.op=="=")
    data.op="";

  ret=lang(data.k, options.count)+
    options['value_separator']+
    data.op+
    lang(data.k+"="+data.v, options.count);

  return ret;
}

/**
 * parse data
 * @param string|array string(s) to translate
 *   variants:
 *   - instance of class tags
 *   - "k=v" or "k>=v"
 *   - ["k1=v1", "k2>=v2"]
 *   - {"k1"=>"v1", "k2"=>"v2"}
 *   - {"k"=>key, "v"=>value[, "op"=>operator]}
 */
function _tags_format_parse(data) {
  var _data=[];
  var m;

  if(typeof data=="string") {
    // "highway=motorway" or "population>=1000"
    if(m=data.match(/^([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/))
      _data=[{ k: m[1], v: m[3], op: m[2] }];
  }
  else if((typeof data=="object")&&data.k&&data.v) {
    // array("k"=>"highway", "v"=>"motorway")
    _data=data;
    _data.op="=";
  }
  else if((typeof data=="object")&&data.k&&data.v&&data.op) {
    // array("k"=>"population", "v"=>"1000", "op"=>">=")
    _data=data;
  }
  else if((typeof data=="object")&&data.data&&data.data()) {
    // tags-instance
    data=data.data();
    for(var i in data) {
      _data.push({ k: i, v: data[i], op: "=" });
    }
  }
  else if((typeof data=="object")&&data.length) {
    // array("highway=motorway", "population>=1000")
    for(var i=0; i<data.length; i++) {
      if(m=data[i].match(/^([^><=!]*)(=|>|<|>=|<=|!=)([^><=!].*)$/))
        _data.push({ k: m[1], v: m[3], op: m[2] });
    }
  }
  else if(typeof data=="object") {
    for(var i in data) {
      _data.push({ k: i, v: data[i], op: "=" });
    }
  }
  else {
    alert("tags_format::_tags_format_parse: Can't parse data");
  }

  return _data;
}

