function layer_ol4pgm_category(id, url) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  this.ol4pgm = new ol4pgmLayer({
    url: url,
    single_url: id + ".py?id={id}&zoom={zoom}&format=geojson-separate&srs=3857",
    maxZoom: 17,
    tileSize: 1024,
    visible: false
  }, map);
  this.ol4pgm.onchange = this.write_div.bind(this);

  // TODO: maybe register_layer or so?
  layers[this.id] = this;

  this.tags.set("name", id);
  // shall_reload

  this.shall_reload = function(list, parent_div, viewbox) {
    var div=parent_div.child_divs[this.id];

    if(!div.open)
      return;

    this.write_div();
  }

  this.inherit_write_div=this.write_div;
  this.write_div=function(div) {
    this.inherit_write_div(div);

    if(!div)
      return;
    if(!div.open)
      return;

    dom_clean(div.data);

    show_list = this.ol4pgm.getFeaturesInExtent();
    for(var i=0; i<show_list.length; i++) {
      show_list[i] = new object_ol4pgm(show_list[i], this);
    }

    new list(div.data, show_list, null, { });
  }

  this.search_object=function(id, callback) {
    this.ol4pgm.getFeature(id, function(callback, feature) {
      if(feature)
        callback(new object_ol4pgm(feature, this));
      else
        callback(null);
    }.bind(this, callback));

    return null;
  }

  // unhide_category
  this.on_unhide_category=function(div) {
    this.ol4pgm.setVisible(true);
  }

  // hide_category
  this.on_hide_category=function(div) {
    this.ol4pgm.setVisible(false);
  }
}
