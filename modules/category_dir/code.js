var category_dir_cache = {};

function load_category_dir(repo, id, branch, repository, data) {
  if(!branch)
    branch = "master";

  if(!(repo in category_dir_cache))
    category_dir_cache[repo] = {};

  if(!(id in category_dir_cache[repo]))
    category_dir_cache[repo][id] = {}

  if(!(branch in category_dir_cache[repo][id]))
    category_dir_cache[repo][id][branch] = new category_dir(repo, id, branch, repository, data);

  return category_dir_cache[repo][id][branch];
}

function category_dir(repo, id, branch, repository, data) {
  this.inheritFrom=category;
  this.inheritFrom(id);

  Eventify.enable(this);

  this.repo_id = repo;
  this.id = id;
  this.branch_id = branch;


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
        this.categories[k] = load_mapcss_category(this.repo.id, category_data.id, this.branch, this, category_data);
        break;
      case 'dir':
        this.categories[k] = load_category_dir(this.repo.id, category_data.id, this.branch, this, category_data);
        break;
      default:
        alert('unknown category type "' + category_data.type + '"');
    }

    this.register_sub_category(this.categories[k].Layer());
  }

  this.trigger("load");
  this.is_loaded = true;

  if(callback)
    callback();
}

this.title = function() {
  return this.id;
}

this.Layer = function() {
  return this;
}

// constructor
  if(repository) {
    this.repo = repository;
    this._data = data;
    this.load_sub_categories();
  }
  else {
    get_category_repository(repo, branch, function(ob) {
      this.repo = ob;
      this.load();
    }.bind(this));
  }
}
