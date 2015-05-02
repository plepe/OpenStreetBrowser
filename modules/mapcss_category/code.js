var mapcss_category_cache = {};
function get_mapcss_category(repo, id, branch, callback) {
  if(!branch)
    branch = "master";

  if(!(repo in mapcss_category_cache))
    mapcss_category_cache[repo] = {};

  if(!(id in mapcss_category_cache[repo]))
    mapcss_category_cache[repo][id] = {}

  if(!(branch in mapcss_category_cache[repo][id]))
    mapcss_category_cache[repo][id][branch] = new mapcss_Category(repo, id, branch);

  var ob = mapcss_category_cache[repo][id][branch];
  if(ob.is_loaded)
    callback(ob);
  else
    ob.once('load', function(callback, ob, data) {
      callback(ob);
    }.bind(this, callback, ob));

  return null;
}

function load_mapcss_category(repo, id, branch, repository, data) {
  if(!branch)
    branch = "master";

  if(!(repo in mapcss_category_cache))
    mapcss_category_cache[repo] = {};

  if(!(id in mapcss_category_cache[repo]))
    mapcss_category_cache[repo][id] = {}

  if(!(branch in mapcss_category_cache[repo][id]))
    mapcss_category_cache[repo][id][branch] = new mapcss_Category(repo, id, branch, repository, data);

  return mapcss_category_cache[repo][id][branch];
}

function mapcss_Category(repo, id, branch, repository, data) {
  Eventify.enable(this);
  this.is_loaded = false;

  this.id = id;

  if(repository) {
    this.repo = repository;
    this._data = data;
    this.is_loaded = true;
    this.trigger("load", data);
  }
  else {
    get_category_repository(repo, branch, function(ob) {
      this.repo = ob;
      this.load();
    }.bind(this));
  }
}

mapcss_Category.prototype.title = function() {
  var data = this.data();

  if(('info' in data) && ('meta' in data.info) && ('title' in data.info.meta))
    return data.info.meta.title;

  return this.id;
}

mapcss_Category.prototype.load = function(callback) {
  new ajax_json("mapcss_category_load", { repo: this.repo.id, id: this.id, branch: this.repo.branch }, function(callback, data) {
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
    title: "Edit category '" + this.id + "'",
    onsave: this.save.bind(this)
  });

  // force loading of current version
  this.load(function() {
    this.editor.set_data(this.data());
  }.bind(this));
}

mapcss_Category.prototype.save = function(data) {
  new ajax_json("mapcss_category_save", { repo: this.repo.id, id: this.id, branch: this.repo.branch }, data, function(result) {
    // force reload
    this.load();
    // force reload of category repository
    this.repo.load();

    if(result.save !== true) {
      alert("An error occured while saving:\n" + result.message);
    }
    else {
      alert(lang("saved"));
      this.editor.close();
    }
  }.bind(this));

  return false;
}

mapcss_Category.prototype.Layer = function() {
  if(!this.layer) {
    var url_param = [];
    ajax_build_request({
         repo: this.repo.id,
         category: this.id,
         branch: this.repo.branch
      }, null, url_param);
    url_param = url_param.join("&");

    this.layer = new layer_ol4pgm_category(this.id, {
      url: "data.php?" + url_param + "&x={x}&y={y}&z={z}&format=geojson-separate&tilesize=1024&srs=3857",
      single_url: "data.php?" + url_param + "&id={id}&zoom={zoom}&format=geojson-separate&srs=3857"
    });
  }

 return this.layer;
}
