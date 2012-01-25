var overlays_layers={};

function overlays_register(id, overlay, options) {
  overlays_layers[id]=overlay;
  overlay.id=id;
  if(!options)
    options={};
  overlay.options=options;

  overlay.events.register("visibilitychanged", overlay, overlays_visibility_change);

  map.addLayer(overlay);

  layers_reorder();

  call_hooks("overlays_registered", overlay, id);
}

function overlays_permalink(permalink) {
  var list=[];

  for(var i in overlays_layers) {
    if(overlays_layers[i].visibility)
      list.push(i);
  }

  permalink.overlays=list.join(",");
}

function overlays_hash_changed(new_hash) {
  if(!new_hash.overlays)
    return;

  var list=new_hash.overlays.split(",");
  for(var i=0; i<list.length; i++) {
    if(overlays_layers[list[i]])
      overlays_layers[list[i]].setVisibility(true);
  }
}

function overlays_hide_category(dummy, category) {
  var overlays_tag=category.tags.get("overlays");

  if(overlays_tag) {
    overlays_tag=split_semicolon(overlays_tag);

    for(var i=0; i<overlays_tag.length; i++) {
      var id=overlays_tag[i];
      
      if(overlays_layers[id]) {
	overlays_layers[id].setVisibility(false);
      }
    }
  }
}

function overlays_unhide_category(dummy, category) {
  var overlays_tag=category.tags.get("overlays");

  if(overlays_tag) {
    overlays_tag=split_semicolon(overlays_tag);

    for(var i=0; i<overlays_tag.length; i++) {
      var id=overlays_tag[i];
      
      if(overlays_layers[id]) {
	overlays_layers[id].setVisibility(true);
      }
    }
  }
}

// nameclash with overlays_init() defined in www/inc/overlays.js
function overlays_init1() {
  if(overlays_add) for(var i in overlays_add) {
    var options=overlays_add[i][1];
    options.transparent=true;
    options.isBaseLayer=false;
    if(typeof options.visibility=="undefined")
      options.visibility=false;

    var layer=new OpenLayers.Layer.OSM(t("overlays:"+i), overlays_add[i][0], options);
    overlays_register(i, layer);
  }
}

register_hook("get_permalink", overlays_permalink);
register_hook("hash_changed", overlays_hash_changed);
register_hook("init", overlays_init1);
register_hook("hide_category", overlays_hide_category)
register_hook("unhide_category", overlays_unhide_category)
