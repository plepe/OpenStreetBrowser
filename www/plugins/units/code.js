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
