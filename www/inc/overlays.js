var overlays_layers;
var drag_feature;
var drag_layer;

function finish_drag(feature) {
  var pos=feature.geometry.getCentroid();
  if(feature.ob&&feature.ob.finish_drag)
    feature.ob.finish_drag(pos);

  call_hooks("finish_drag", feature, pos);
}

function object_select(ev) {
  var feature=ev.feature;
  var pos=feature.geometry.getCentroid();
  if(feature.ob&&feature.ob.object_select)
    feature.ob.object_select(pos);

  call_hooks("object_select", feature, pos);
}

function object_unselect(ev) {
  var feature=ev.feature;
  var pos=feature.geometry.getCentroid();
  if(feature.ob&&feature.ob.object_unselect)
    feature.ob.object_unselect(pos);

  call_hooks("object_unselect", feature, pos);
}


function list_overlays() {
  var list=[];
  for(var i in overlays_layers) {
    if(overlays_layers[i].visibility)
      list.push(i);
  }
  return list;
}

function check_overlays(data) {
  var new_layers=[];

  if(data) {
    var l=data.getElementsByTagName("overlay");
    for(var i=0; i<l.length; i++) {
      new_layers[l[i].getAttribute("id")]=1;
    }
  }

  for(var i in overlays_layers) {
    if((overlays_layers[i].visibility==true)&&(!new_layers[i])) {
      overlays_layers[i].setVisibility(false);
    }
    else if((overlays_layers[i].visibility==false)&&(new_layers[i])) {
      overlays_layers[i].setVisibility(true);
    }
  }
}

function overlays_init() {
  overlays_layers = {
    ch: new OpenLayers.Layer.OSM("Cycle &amp; Hiking", "tiles/overlay_ch/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    pt: new OpenLayers.Layer.OSM("Public Transport", "tiles/overlay_pt/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    car: new OpenLayers.Layer.OSM("Individual Transport", "tiles/overlay_car/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    food: new OpenLayers.Layer.OSM("Food &amp; Drink", "tiles/overlay_food/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    culture: new OpenLayers.Layer.OSM("Culture &amp; Tourism", "tiles/overlay_culture/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    services: new OpenLayers.Layer.OSM("Services", "tiles/overlay_services/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    shop: new OpenLayers.Layer.OSM("Shops", "tiles/shop/", {numZoomLevels: 19, isBaseLayer: false, visibility: false }),
    agri_ind: new OpenLayers.Layer.OSM("Agriculture &amp; Industry", "tiles/agri_ind/", {numZoomLevels: 19, isBaseLayer: false, visibility: false })
  };

  vector_layer=new OpenLayers.Layer.Vector(t("overlay:data"), {});
  vector_layer.setOpacity(0.7);
  drag_layer=new OpenLayers.Layer.Vector(t("overlay:draggable"), {});

  mod_feature=new OpenLayers.Control.ModifyFeature(drag_layer);

  for(var i in overlays_layers) {
    map.addLayer(overlays_layers[i]);
  }
  map.addLayer(vector_layer);
  map.addLayer(drag_layer);
  map.addControl(mod_feature);

  mod_feature.mode |= OpenLayers.Control.ModifyFeature.DRAG;
  mod_feature.dragComplete=finish_drag;
  drag_layer.events.on({
    'featureselected': object_select,
    'featureunselected': object_unselect
  });

  mod_feature.activate();
}
