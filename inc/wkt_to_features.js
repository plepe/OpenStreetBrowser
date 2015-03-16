function wkt_to_features(text) {
  var ret = null;

  // For some reason OpenLayers doesn't understand MULTIPOINTs ...
  // convert them to GEOMETRYCOLLECTION of POINTs
  var match;
  while(match=text.match(/^(.*)MULTIPOINT\(([0-9\., ]+)\)(.*)$/)) {
    text =
      match[1]+
      "GEOMETRYCOLLECTION(POINT("+
      match[2].split(",").join("),POINT(")+
      "))"+match[3];
  }

  var parser = new ol.format.WKT();
  ret = parser.readFeatures(text);

  if(!ret)
    ret = [];
  else if(!ret.length)
    ret = [this._geo];

  ret = array_remove_undefined(array_unfold(ret));

  return ret;
}
