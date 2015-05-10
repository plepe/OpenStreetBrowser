function layer_ol4pgm_category(id, options) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  this.ol4pgm = new ol4pgmLayer({
    url: options.url,
    single_url: options.single_url,
    maxZoom: 17,
    tileSize: 1024,
    visible: false,
    icons_parent_path: 'icons/'
  }, map);
  this.ol4pgm.onchange = this.write_div.bind(this);

  // TODO: maybe register_layer or so?
  layers[this.id] = this;

  if(options.meta && options.meta.title)
    this.tags.set("name", options.meta.title);
  else
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
      if(feature) {
        // TODO: when leaving object, unset visibility
        this.ol4pgm.setVisible(true);

        callback(new object_ol4pgm(feature, this));
      }
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
