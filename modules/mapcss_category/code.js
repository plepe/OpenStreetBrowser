var mapcss_category_cache = {};
function get_mapcss_category(id, callback) {
  if(!(id in mapcss_category_cache))
    mapcss_category_cache[id] = new mapcss_Category(id);

  var ob = mapcss_category_cache[id];
  if(ob.is_loaded)
    callback(ob);
  else
    ob.once('load', function(callback, ob, data) {
      callback(ob);
    }.bind(this, callback, ob));

  return null;
}

function load_mapcss_category(id, repository, data) {
  if(!(id in mapcss_category_cache))
    mapcss_category_cache[id] = new mapcss_Category(id, repository, data);

  return mapcss_category_cache[id];
}

function mapcss_Category(id, repository, data) {
  this.inheritFrom=category;
  this.inheritFrom(id);

this.title = function() {
  var data = this.data();

  if(data && ('meta' in data) && ('title' in data.meta))
    return data.meta.title;

  return this.pure_id;
}

this.load = function(callback) {
  new ajax_json("mapcss_category_load", { id: this.id }, function(callback, data) {
    this._data = data;

    this.is_loaded = true;
    this.trigger("load", data);

    this.tags.set("name", this.title());

    if(callback)
      callback();
  }.bind(this, callback));
}

this.data = function() {
  return this._data;
}

this.edit = function() {
  var form_def = {
    'content': {
      'type': 'textarea',
      'name': "MapCSS code",
      'hide_label': true,
      'req': true
    },
    'commit_msg': {
      'type': 'text',
      'name': "Commit message"
    }
  };

  this.editor = new editor({
    form_def: form_def,
    title: "Edit category '" + this.pure_id + "'",
    onsave: this.save.bind(this)
  });

  // force loading of current version
  this.load(function() {
    this.editor.set_data(this.data());
  }.bind(this));
}

this.save = function(data) {
  new ajax_json("mapcss_category_save", { id: this.id }, data, function(result) {
    if(!result) {
      alert("An unknown error occured when saving.");
    }
    else {
      var txt = "";
      var success = false;

      if([0, null, undefined].indexOf(result.error) != -1) {
        txt += "Saved. Messages:";
        success = true;
      }
      else
        txt += "An error occured when saving:";

      for(var k in result.message)
        txt += "\n\n" + k + ":\n" + result.message[k];

      alert(txt);

      if(success) {
        this.editor.close();

        // force reload
        this.load();
        // force reload of category repository
        this.repo.load();
      }

      return;
    }
  }.bind(this));

  return false;
}

this.Layer = function() {
  if(!this.layer) {
    var url_param = [];
    ajax_build_request({
         category: this.id,
      }, null, url_param);
    url_param = url_param.join("&");

    var data = this.data();

    this.layer = new ol4pgmLayer({
      url: "data.php?" + url_param + "&x={x}&y={y}&z={z}&tilesize=1024&srs=3857",
      single_url: "data.php?" + url_param + "&id={id}&zoom={zoom}&format=geojson-separate&srs=3857",
      maxZoom: 17,
      tileSize: 1024,
      visible: false,
      icons_parent_path: 'icons/'
    }, map);
    this.layer.onchange = this.write_div.bind(this);
  }

 return this.layer;
}

this.shall_reload = function(list, parent_div, viewbox) {
  if((!parent_div.child_divs) || (!parent_div.child_divs[this.id]))
    return;

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

  show_list = this.Layer().getFeaturesInExtent();
  for(var i=0; i<show_list.length; i++) {
    show_list[i] = new object_ol4pgm(show_list[i], this);
  }

  new list(div.data, show_list, null, { });
}

register_hook("contextmenu_add_items", function(list, pos) {
  if((!this.Layer) || (!this.Layer().getVisible()))
    return;

  var pixel = map.getPixelFromCoordinate(ol.proj.transform(pos, 'EPSG:4326', 'EPSG:3857'));
  var items = this.Layer().featuresAtPixel(pixel);
  for(var i = 0; i < items.length; i++) {
    var item = items[i];
    var result = item.getProperties().results[0];

    list.push(new contextmenu_entry({
        img: result['final-list-icon-image'] ? 'icons/' + escape(result['final-list-icon-image']) : null,
        text: result['list-text']
      }, function(item) {
        set_url({ obj: this.id + "/" + item.getProperties()['osm:id'] });
      }.bind(this, item)));
  }
}.bind(this), this);

this.search_object=function(id, callback) {
  this.Layer().getFeature(id, function(callback, feature) {
    if(feature) {
      // TODO: when leaving object, unset visibility
      this.layer.setVisible(true);

      callback(new object_ol4pgm(feature, this));
    }
    else
      callback(null);
  }.bind(this, callback));

  return null;
}

// unhide_category
this.on_unhide_category=function(div) {
  this.Layer().setVisible(true);
}

// hide_category
this.on_hide_category=function(div) {
  this.Layer().setVisible(false);
}

// constructor
  Eventify.enable(this);
  this.is_loaded = false;

  this.id = id;
  var p = id.split("/");
  this.repo_id = p.splice(0, 1)[0];
  this.pure_id = p.join("/");

  // TODO: maybe register_layer or so?
  layers[this.id] = this;

  if(repository) {
    this.repo = repository;
    this._data = data;
    this.is_loaded = true;
    this.trigger("load", data);

    this.tags.set("name", this.title());
  }
  else {
    get_category_repository(this.repo_id, function(ob) {
      this.repo = ob;
      this.load();
    }.bind(this));
  }
}
