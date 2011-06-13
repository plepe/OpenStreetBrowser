// CSS: Functions for CSS handling

/**
 * add_css_class - adds a class to a DOM object
 * @param object dom_ob A dom object which should get an additional css class
 * @param text class The additional class
 * @return array List of all classes of the object
 */
function add_css_class(dom_ob, cl) {
  var classes=dom_ob.className.split(" ");

  if(!in_array(cl, classes)) {
    classes.push(cl);
  }

  dom_ob.className=classes.join(" ");

  return classes;
}

/**
 * del_css_class - adds a class to a DOM object
 * @param object dom_ob A dom object which should get an additional css class
 * @param text class The additional class
 * @return array List of all classes of the object
 */
function del_css_class(dom_ob, cl) {
  var classes=dom_ob.className.split(" ");
  var p;

  if((p=array_search(cl, classes))!==false) {
    classes=array_remove(classes, p);
  }

  dom_ob.className=classes.join(" ");

  return classes;
}

/**
 * Save css style to string
 */
function css_style_to_string(style) {
  var ret=[];

  for(var i in style) {
    ret.push(i+": "+style[i]+";");
  }

  return ret.join(" ");
}

/**
 * Read css style from string (e.g. "line-width: 2; color: #ff0000;")
 */
function css_style_from_string(str) {
  var parts;
  var ret={};

  parts=str.split(";");
  for(var i=0; i<parts.length; i++) {
    var x=parts[i].match("^[ ]*([^:]+)[ ]*:[ ]*(.*)[ ]*$");
    if(x) {
      ret[x[1]]=x[2];
    }
  }

  return ret;
}
