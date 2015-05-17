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
  Eventify.enable(this);
  this.is_loaded = false;

  this.id = id;
  var p = id.split("/");
  this.repo_id = p.splice(0, 1)[0];
  this.pure_id = p.join("/");

  if(repository) {
    this.repo = repository;
    this._data = data;
    this.is_loaded = true;
    this.trigger("load", data);
  }
  else {
    get_category_repository(this.repo_id, function(ob) {
      this.repo = ob;
      this.load();
    }.bind(this));
  }
}

mapcss_Category.prototype.title = function() {
  var data = this.data();

  if(('meta' in data) && ('title' in data.meta))
    return data.meta.title;

  return this.pure_id;
}

mapcss_Category.prototype.load = function(callback) {
  new ajax_json("mapcss_category_load", { id: this.id }, function(callback, data) {
    this._data = data;

    this.is_loaded = true;
    this.trigger("load", data);

    if(callback)
      callback();
  }.bind(this, callback));
}

mapcss_Category.prototype.data = function() {
  return this._data;
}

mapcss_Category.prototype.edit = function() {
  var form_def = {
    'content': {
      'type': 'textarea',
      'name': "MapCSS code",
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

mapcss_Category.prototype.save = function(data) {
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

mapcss_Category.prototype.search_object = function(object_id, callback) {
  return this.Layer().search_object(object_id, callback);
}

mapcss_Category.prototype.Layer = function() {
  if(!this.layer) {
    var url_param = [];
    ajax_build_request({
         category: this.id,
      }, null, url_param);
    url_param = url_param.join("&");

    var data = this.data();

    this.layer = new layer_ol4pgm_category(this.id, {
      meta: data.meta,
      url: "data.php?" + url_param + "&x={x}&y={y}&z={z}&format=geojson-separate&tilesize=1024&srs=3857",
      single_url: "data.php?" + url_param + "&id={id}&zoom={zoom}&format=geojson-separate&srs=3857"
    });
  }

 return this.layer;
}
