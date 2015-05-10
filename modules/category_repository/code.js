var category_repository_cache = {};

function get_category_repository(id, branch, callback) {
  if(!(id in category_repository_cache))
    category_repository_cache[id] = {};

  if(!branch)
    branch = "master";

  if(!(branch in category_repository_cache[id]))
    category_repository_cache[id][branch] = new CategoryRepository(id, branch);

  var ob = category_repository_cache[id][branch];
  if(ob.is_loaded)
    callback(ob);
  else
    ob.once('load', function(callback, ob, data) {
      callback(ob);
    }.bind(this, callback, ob));

  return null;
}

function CategoryRepository(id, branch) {
  Eventify.enable(this);
  this.is_loaded = false;

  this.id = id;
  if(branch)
    this.branch = branch;
  else
    this.branch = "master";

  this.load();
}

CategoryRepository.prototype.load = function(callback) {
  new ajax_json("category_repository_load", { id: this.id, branch: this.branch }, function(callback, data) {
    this._data = data;
    this.categories = null;

    this.is_loaded = true;
    this.trigger("load", data);

    if(callback)
      callback();
  }.bind(this, callback));
}

CategoryRepository.prototype.get_categories = function(callback) {
  if(!this.categories) {
    this.categories = {};

    for(var k in this._data.categories) {
      var category_data = this._data.categories[k];

      this.categories[k] = load_mapcss_category(this.id, k, this.branch, this, category_data);
    }

    this.categories
  }

  callback(this.categories);
}

CategoryRepository.prototype.data = function() {
  return this._data;
}

register_hook("search_object", function(ret, id, callback) {
  var id_parts = id.split("/");

  if(id_parts.length == 1)
    return;

  var category_id = id_parts.slice(0, id_parts.length - 1).join("/");
  var repo_id = "main"; //id_parts[0];
  var object_id = id_parts[id_parts.length - 1];

  var category = load_mapcss_category(repo_id, category_id);

  // if category is loaded, it will react on search_object request by itself
  if(!category.is_loaded) {
    category.once('load', function(object_id, callback) {
      category_root.register_sub_category(category.Layer());
      category.search_object(object_id, callback);
    }.bind(this, object_id, callback));

    ret.push(null);
  }
});
