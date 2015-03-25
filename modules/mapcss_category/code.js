var mapcss_category_cache = {};
function get_mapcss_category(repo, id, branch) {
  if(!branch)
    branch = "master";

  if(!(repo in mapcss_category_cache))
    mapcss_category_cache[repo] = {};

  if(!(id in mapcss_category_cache[repo]))
    mapcss_category_cache[repo][id] = {}

  if(!(branch in mapcss_category_cache[repo][id]))
    mapcss_category_cache[repo][id][branch] = new mapcss_Category(repo, id, branch);

  return mapcss_category_cache[repo][id][branch];
}

function mapcss_Category(repo, id, branch) {
  this.repo = get_category_repository(repo, branch);
  this.id = id;

  this.data_callbacks = [];

  this.load();
}

mapcss_Category.prototype.load = function(data) {
  new ajax_json("mapcss_category_load", { repo: this.repo.id, id: this.id, branch: this.repo.branch }, function(data) {
    this._data = data;

    for(var i = 0; i < this.data_callbacks.length; i++) {
      this.data_callbacks[i](this._data);
    }
    this.data_callbacks = [];
  }.bind(this));
}

mapcss_Category.prototype.data = function(callback, force) {
  if(this._data) {
    if(force) {
      this.load();
    }
    else {
      callback(this._data);
      return;
    }
  }

  this.data_callbacks.push(callback);
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
    title: "Edit category '" + this.id + "'",
    onsave: this.save.bind(this)
  });

  this.data(this.editor.set_data.bind(this.editor));
}

mapcss_Category.prototype.save = function(data) {
  new ajax_json("mapcss_category_save", { repo: this.repo.id, id: this.id, branch: this.repo.branch }, data, function(result) {
    // force reload
    this.data(function() {}, true);

    alert("Saved.");

    this.editor.close();
  }.bind(this));

  return false;
}
