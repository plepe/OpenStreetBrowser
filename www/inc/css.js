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
