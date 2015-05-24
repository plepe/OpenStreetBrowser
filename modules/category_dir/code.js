var category_dir_cache = {};

function load_category_dir(id, repository, data) {
  if(!(id in category_dir_cache))
    category_dir_cache[id] = new category_dir(id, repository, data);

  return category_dir_cache[id];
}

function category_dir(id, repository, data) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  Eventify.enable(this);

  var p = id.split("/");
  this.repo_id = p.splice(0, 1)[0];
  this.pure_id = p.join("/");

this.data = function() {
  return this._data;
}

this.load = function(callback) {
  // TODO: load directory structure from server
  this.load_sub_categories(callback);
}

this.load_sub_categories = function(callback) {
  this.categories = {};
  this.sub_categories = [];

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

  this.trigger("load");
  this.is_loaded = true;

  if(callback)
    callback();
}

this.title = function() {
  if(this._data.meta && this._data.meta.title)
    return this._data.meta.title;

  return this.pure_id;
}

this.get_category = function(id, callback) {
  for(var k in this.categories) {
    if(this.categories[k].id == id) {
      callback(this.categories[k]);
      return;
    }
  }

  callback(null);
}

// constructor
  if(repository) {
    this.repo = repository;
    this._data = data;
    this.load_sub_categories();

    this.tags.set("name", this.title());
  }
  else {
    get_category_repository(repo, function(ob) {
      this.repo = ob;
      this.load();
    }.bind(this));
  }
}
