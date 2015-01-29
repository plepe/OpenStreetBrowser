function get_viewbox() {
  var x=map.getView().calculateExtent(map.getSize());
  return x.left +","+ x.top +","+ x.right +","+ x.bottom;
}

function get_zoom() {
  return map.zoom;
}
