var layers={};

function register_layer(id, layer) {
  layers[id]=layer;

  map.addLayer(layer);

  overlays_reorder();
}

function layers_permalink(permalink) {
  var list=[];

  for(var i in layers) {
    if(layers[i].visibility)
      list.push(i);
  }

  permalink.layers=list.join(",");
}

function layers_hash_changed(new_hash) {
  if(!new_hash.layers)
    return;

  var list=new_hash.layers.split(",");
  for(var i=0; i<list.length; i++) {
    if(layers[list[i]])
      layers[list[i]].setVisibility(true);
  }
}

register_hook("get_permalink", layers_permalink);
register_hook("hash_changed", layers_hash_changed);
