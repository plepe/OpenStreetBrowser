// return formated string for value
function units_format_length(val) {
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
