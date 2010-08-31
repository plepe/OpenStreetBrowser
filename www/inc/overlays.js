var overlays_layers;
var drag_feature;
var drag_layer;
var overlays_list={};

function overlay(id, _tags) {
  // show
  this.show=function() {
    this.layer.setVisibility(true);

    if(vector_layer) {
      var tindex=map.getLayerIndex(this.layer);
      var index=map.getLayerIndex(vector_layer);

      if(tindex>index)
	map.setLayerIndex(this.layer, index);
      else
	map.setLayerIndex(this.layer, index-1);
    }
  }

  // hide
  this.hide=function() {
    this.layer.setVisibility(false);
  }

  // set_version
  this.set_version=function(version) {
    this.version=version;

    if(this.layer.visibility) {
      this.hide();
      this.show();
    }
  }

  // set_name
  this.set_name=function(name) {
    this.layer.setName(name);
  }

  // build_url
  this.build_url=function(bounds) {
    var res = map.getResolution();
    var x = Math.round ((bounds.left - this.layer.maxExtent.left) / (res * this.layer.tileSize.w));
    var y = Math.round ((this.layer.maxExtent.top - bounds.top) / (res * this.layer.tileSize.h));
    var z = map.getZoom();

    var path = "tiles/"+ this.id + "/" + z + "/" + x + "/" + y + ".png?"+ this.version;
    
    return path;
  }

  // register category
  this.register_category=function(category) {
    this.category_list[category.id]=category;
  }

  // unregister category
  this.unregister_category=function(category) {
    delete(this.category_list[category.id]);

    if(keys(this.category_list).length==0)
      this.destroy();
  }

  /// destroy (destructor)
  this.destroy=function() {
    map.removeLayer(this.layer);
    delete(overlays_list[this.id]);
  }

  // constructor
  this.id=id;
  if(!_tags)
    _tags=new tags();
  this.tags=_tags;
  this.category_list={};

  var name=this.tags.get_lang("name", ui_lang);
  if(!name)
    name=this.id;

  this.layer=new OpenLayers.Layer.OSM(name, "tiles/"+this.id, {numZoomLevels: 19, isBaseLayer: false, visibility: false, getURL: this.build_url.bind(this) });
  map.addLayer(this.layer);
  overlays_list[this.id]=this;

  this.maxExtent=new OpenLayers.Bounds(-20037508.3427892,-20037508.3427892,20037508.3427892,20037508.3427892);
  this.tileSize={w: 256, h: 256};
}

function get_overlay(id) {
  return overlays_list[id];
}

function finish_drag(feature, pos) {
  call_hooks("finish_drag", feature, pos);
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

  vector_layer=new OpenLayers.Layer.Vector("Data", {});
  vector_layer.setOpacity(0.7);
  drag_layer=new OpenLayers.Layer.Vector("Draggable", {});

  drag_feature=new OpenLayers.Control.DragFeature(drag_layer);

  for(var i in overlays_layers) {
    map.addLayer(overlays_layers[i]);
  }
  map.addLayer(vector_layer);
  map.addLayer(drag_layer);
  map.addControl(drag_feature);
  drag_feature.activate();
  drag_feature.onComplete=finish_drag;
}
