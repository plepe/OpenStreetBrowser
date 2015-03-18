var layers={};

function register_layer(id, layer) {
  layers[id]=layer;

  map.addLayer(layer);
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

register_hook("search_object", function(ret, id, callback) {
  var m;

  if(m = id.match(/^(.*)\/([^\/]+)$/)) {
    var layer_id = m[1];
    var ob_id = m[2];

    if(!(layer_id in layers)) {
      // TODO: load layer
    }

    if(layer_id in layers) {
      if(layers[layer_id].search_object) {
        var ob = layers[layer_id].search_object(ob_id, callback);
        if(ob != false)
          ret.push(ob);
      }
    }
  }
});

register_hook("get_permalink", layers_permalink);
register_hook("hash_changed", layers_hash_changed);
