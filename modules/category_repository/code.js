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

    this.is_loaded = true;
    this.trigger("load", data);

    if(callback)
      callback();
  }.bind(this, callback));
}

CategoryRepository.prototype.data = function() {
  return this._data;
}
