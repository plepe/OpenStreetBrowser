var category_repository_cache = {};

function get_category_repository(id, callback) {
  category_repository_cache[id] = new CategoryRepository(id);

  var ob = category_repository_cache[id];
  if(ob.is_loaded)
    callback(ob);
  else
    ob.once('load', function(callback, ob, data) {
      callback(ob);
    }.bind(this, callback, ob));

  return null;
}

function CategoryRepository(id, data) {
  this.inheritFrom=category;
  this.inheritFrom(id);

this.load = function(callback) {
  new ajax_json("category_repository_load", { id: this.id }, this.load_callback.bind(this, callback));
}

this.fork = function(branch_id, callback) {
  new ajax_json("category_repository_fork", { id: this.id, branch: branch_id }, this.load_callback.bind(this, callback));
}

this.load_callback = function(callback, data) {
  this._data = data;
  this.categories = null;

  this.is_loaded = true;
  this.trigger("load", data);

  if(callback)
    callback();
}

this.get_categories = function(callback) {
  if(!this.categories) {
    this.categories = {};

    for(var k in this._data.categories) {
      var category_data = this._data.categories[k];

      switch(category_data.type) {
        case 'mapcss_category':
          this.categories[k] = load_mapcss_category(category_data.id, this, category_data);
          break;
        case 'dir':
          this.categories[k] = load_category_dir(category_data.id, this, category_data);
          break;
        default:
          alert('unknown category type "' + category_data.type + '"');
      }

      this.register_sub_category(this.categories[k]);
    }
  }

  callback(this.categories);
}

this._get_category = function(id_parts, list) {
  if(id_parts[0] in list) {
    if(id_parts.length == 1)
      return list[id_parts[0]];
    else
      return this._get_category(id_parts.slice(1), list[id_parts[0]].categories);
  }

  return null;
}

this.get_category = function(id, callback) {
  this.get_categories(function(id, callback) {
    var id_parts = id.split("/");
    if(id_parts[0] != this.id)
      return null;

    var ret = this._get_category(id_parts.slice(1), this.categories, callback);
    callback(ret);
  }.bind(this, id, callback));
}

this.data = function() {
  return this._data;
}

this.shall_reload = function(list, parent_div, viewbox) {
  if((!parent_div.child_divs) || (!parent_div.child_divs[this.id]))
    return;

  var div=parent_div.child_divs[this.id];

  for(var i=0; i<this.sub_categories.length; i++) {
    this.sub_categories[i].shall_reload(list, div.sub, viewbox);
  }
}

// constructor
  Eventify.enable(this);
  this.is_loaded = false;

  this.id = id;

  if(data) {
    this.load_callback(null, data);
  }
  else {
    this.load();
  }
}
