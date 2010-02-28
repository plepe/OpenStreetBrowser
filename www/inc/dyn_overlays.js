var dyn_overlay={};
var dyn_overlay_imgs={};

function dyn_overlay_show(cat, match_ob) {
  var geo=match_ob.tags.get("geo:center");
  var icon=match_ob.tags.get("icon");
  var id=match_ob.id;
  var category=cat.id;

  if(!geo)
    return;
  if(match_ob.dyn_overlay_vector)
    return;

  var p=new postgis(geo).geo();

  if(!dyn_overlay[category])
    dyn_overlay[category]={ vectors: [], ids: {} };

  if(dyn_overlay[category].ids[id])
    return;

  if(!dyn_overlay_imgs[icon]) {
    var newImg = new Image();
    newImg.src = icon;
    dyn_overlay_imgs[icon]={ width: newImg.width, height: newImg.height };
    delete newImg;
  }

  var f=new OpenLayers.Feature.Vector(p[0], 0, {
    strokeWidth: 4,
    strokeColor: "black",
    externalGraphic: icon,
    graphicWidth: dyn_overlay_imgs[icon].width,
    graphicHeight: dyn_overlay_imgs[icon].height,
    graphicXOffset: -(dyn_overlay_imgs[icon].width+1)/2,
    graphicYOffset: -(dyn_overlay_imgs[icon].height+1)/2,
    fill: "none"
  });
  f.match_ob=match_ob;
  match_ob.dyn_overlay_vector=f;

  dyn_overlay[category].vectors.push(f);
  dyn_overlay[category].ids[id]=true;
}

function dyn_overlays_show(cat) {
  if(!dyn_overlay[cat.id])
    dyn_overlay[cat.id]={ vectors: [], ids: {} };

  vector_layer.addFeatures(dyn_overlay[cat.id].vectors);
}

function dyn_overlays_showall(cat) {
  if(!dyn_overlay[cat.id])
    return;

  vector_layer.addFeatures(dyn_overlay[cat.id].vectors);
}

function dyn_overlays_hide(cat) {
  if(dyn_overlay[cat])
    vector_layer.removeFeatures(dyn_overlay[cat].vectors);
}

register_hook("show_category", dyn_overlays_show);
register_hook("hide_category", dyn_overlays_hide);
register_hook("category_load_match", dyn_overlay_show);
register_hook("category_show_finished", dyn_overlays_showall);
