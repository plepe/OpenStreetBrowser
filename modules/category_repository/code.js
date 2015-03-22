function CategoryRepository(id, branch) {
  this.id = id;
  if(branch)
    this.branch = branch;
  else
    this.branch = "master";

  this.data_callbacks = [];

  this.load();
}

CategoryRepository.prototype.load = function() {
  new ajax_json("category_repository_load", { id: this.id, branch: this.branch }, function(data) {
    this._data = data;

    for(var i = 0; i < this.data_callbacks.length; i++) {
      this.data_callbacks[i](this._data);
    }
    this.data_callbacks = [];
  }.bind(this));
}

CategoryRepository.prototype.data = function(callback, force) {
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
