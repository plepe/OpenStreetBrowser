// returns a value for the passed text ... higher importance, higher value
function importance_value(text) {
  pos=array_search(text, importance_levels);
  if(pos===false)
    return 0;

  return (importance_levels.length-pos)*10-5;
}

// returns a text for the passed value
function importance_text(value) {
  if(value>=importance_levels.length*10)
    return importance_levels[0];
  if(value<=0)
    return importance_levels[importance_levels.length-1];

  return importance_levels[importance_levels.length-Math.round((value+5)/10.0)];
}

// returns translated importance string
function importance_lang(text) {
  return lang("importance:"+text);
}
