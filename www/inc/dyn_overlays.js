var dyn_overlay={};

function dyn_overlay_show(category, place) {
  var geo=place.getAttribute("center");
  var icon=place.getAttribute("icon");
  var id=place.getAttribute("id");

  if(!geo)
    return;

  var p=new postgis(geo).geo();

  if(!dyn_overlay[category])
    dyn_overlay[category]={ vectors: [], ids: {} };

  if(dyn_overlay[category].ids[id])
    return;

  var f=new OpenLayers.Feature.Vector(p[0], 0, {
    strokeWidth: 4,
    strokeColor: "black",
    externalGraphic: icon,
    graphicWidth: 25,
    graphicHeight: 25,
    graphicXOffset: -13,
    graphicYOffset: -13,
    fill: "none"
  });

  dyn_overlay[category].vectors.push(f);
  dyn_overlay[category].ids[id]=true;
}

function dyn_overlays_show(cat) {
  if(!dyn_overlay[cat])
    dyn_overlay[cat]={ vectors: [], ids: {} };

  vector_layer.addFeatures(dyn_overlay[cat].vectors);
}

function dyn_overlays_showall(cat) {
  if(!dyn_overlay[cat])
    return;

  vector_layer.addFeatures(dyn_overlay[cat].vectors);
}

function dyn_overlays_hide(cat) {
  if(dyn_overlay[cat])
    vector_layer.removeFeatures(dyn_overlay[cat].vectors);
}

register_hook("show_category", dyn_overlays_show);
register_hook("hide_category", dyn_overlays_hide);
