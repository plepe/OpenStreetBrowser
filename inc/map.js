function get_viewbox() {
  var x=map.calculateBounds();
  return x.left +","+ x.top +","+ x.right +","+ x.bottom;
}

function get_zoom() {
  return map.zoom;
}
