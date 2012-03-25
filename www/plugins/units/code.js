// return formated string for value
function units_format_length(val) {
  val=parseFloat(val);
  if(val>45000) {
    return lang("units:km", 0, (val/1000).toFixed(0));
  }
  if(val>9000) {
    return lang("units:km", 0, (val/1000).toFixed(1));
  }
  if(val>900) {
    return lang("units:km", 0, (val/1000).toFixed(2));
  }
  if(val>45) {
    return lang("units:m", 0, val.toFixed(0));
  }
  if(val>9) {
    return lang("units:m", 0, val.toFixed(1));
  }

  return lang("units:m", 0, val.toFixed(2));
}

// return formated string for value
function units_format_area(val) {
  val=parseFloat(val);
  if(val>90000000) {
    return lang("units:km2", 0, (val/1000000).toFixed(0));
  }
  if(val>9000000) {
    return lang("units:km2", 0, (val/1000000).toFixed(1));
  }
  if(val>900000) {
    return lang("units:km2", 0, (val/1000000).toFixed(2));
  }
  if(val>90000) {
    return lang("units:ha", 0, (val/10000).toFixed(0));
  }
  if(val>45000) {
    return lang("units:ha", 0, (val/10000).toFixed(1));
  }
  if(val>9000) {
    return lang("units:ha", 0, (val/10000).toFixed(2));
  }
  if(val>900) {
    return lang("units:m2", 0, val.toFixed(0));
  }
  if(val>90) {
    return lang("units:m2", 0, val.toFixed(1));
  }

  return lang("units:m2", 0, val.toFixed(2));
}

// return formated string for time (in seconds)
function units_format_time(val) {
  val=parseFloat(val);
  if(val>6*86400)
    return lang("units:day", 0, (val/86400).toFixed(0));
  if(val>2*86400)
    return lang("units:day", 0, (val/86400).toFixed(1));
  if(val>4*3600)
    return lang("units:hour", 0, (val/3600).toFixed(0));
  if(val>3600)
    return lang("units:hour_min", 0, Math.floor(val/3600), ((val%3600)/60).toFixed(0));
  if(val>1800)
    return lang("units:min", 0, (val/60).toFixed(0));
  if(val>120)
    return lang("units:min_sec", 0, Math.floor(val/60), (val%60).toFixed(0));
  return lang("units:sec", 0, val.toFixed(0));
}

/**
 * translates speed value to prefered display unit
 * @param val speed
 * @param unit unit in which speed is provided. Valid values: "m/s" (default)
 * @return string formated speed value
 */
function units_format_speed(val, unit) {
  if(!unit)
    unit="m/s";

  if(val===null)
    return "?";
  if(isNaN(val))
    return "?";

  if(unit=="m/s")
    val=val*3600/1000;

  var display_unit="km/h";

  if(val<10)
    val=sprintf("%.1f", val);
  else
    val=sprintf("%.0f", val);

  return lang("units:speed:"+display_unit, 0, val);
}

/**
 * translates altitude value to prefered display unit
 * @param val altitude
 * @param unit unit in which altitude is provided. Valid values: "m" (default)
 * @return string formated altitude value
 */
function units_format_altitude(val, unit) {
  if(!unit)
    unit="m";

  if(val===null)
    return "?";
  if(isNaN(val))
    return "?";

//  if(unit=="m")
//    val=val*1;

  var display_unit="m";

  val=sprintf("%.0f", val);

  return lang("units:altitude:"+display_unit, 0, val);
}

/**
 * translates heading value to prefered display unit
 * @param val heading
 * @param unit unit in which heading is provided. Valid values: "deg" (default)
 * @return string formated heading value
 */
function units_format_heading(val, unit) {
  if(!unit)
    unit="deg";

  if(val===null)
    return "?";
  if(isNaN(val))
    return "?";

//  if(unit=="deg")
//    val=val;

  var display_unit="deg-windrose";
  var windrose=lang("units:heading:windrose:"+(((val+337.5)/45.0).toFixed(0)%8));

  val=sprintf("%.0f", val);

  return lang("units:heading:"+display_unit, 0, val, windrose);
}

/**
 * return latitude of position as formatted string
 * @param val position as OpenLayers.Lonlat
 * @return string formated heading value
 */
function units_format_latitude(val) {
  if(val===null)
    return "?";
  if(val.lat===undefined)
    return "?";

  return sprintf("%s %.5f°", lang("units:latitude:"+(val.lat>0?"N":"S")), val.lat);
}

/**
 * return longitude of position as formatted string
 * @param val position as OpenLayers.Lonlat
 * @return string formated heading value
 */
function units_format_longitude(val) {
  if(val===null)
    return "?";
  if(val.lon===undefined)
    return "?";

  return sprintf("%s %.5f°", lang("units:longitude:"+(val.lon<0?"W":"E")), val.lon);
}
