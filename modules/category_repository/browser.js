function CategoryRepositoryBrowser(id, branch) {
  this.win = new win({
    title: "Repository \"" + id + "\"",
    class: "category_repository_browser"
  });
  this.win.content.innerHTML = "Loading ...";
  this.win.onclose = this.close.bind(this);

  this.category_repository = get_category_repository(id, branch);
  this.category_repository.data(this.show.bind(this));
  this.category_repository.onload = function() {
    this.category_repository.data(this.show.bind(this));
  }.bind(this);
}

CategoryRepositoryBrowser.prototype.show = function(data) {
  this.data = data;

  dom_clean(this.win.content);

  var h = document.createElement("h4");
  h.appendChild(document.createTextNode("Categories"));
  this.win.content.appendChild(h);

  var ul = document.createElement("ul");

  for(var k in this.data.categories) {
    var category = this.data.categories[k];
    var li = document.createElement("li");

    var a = document.createElement("a");
    a.onclick = function(repo, id, branch) {
      var cat = new get_mapcss_category(repo, id, branch);
      var layer = cat.Layer()

      if(layer)
        category_root.register_sub_category(layer);
      else
        alert("Can't create layer from category!");

    }.bind(this, this.category_repository.id, k, this.category_repository.branch);
    a.appendChild(document.createTextNode(k));
    li.appendChild(a);

    var a = document.createElement("a");
    a.onclick = function(repo, id, branch) {
      var cat = new get_mapcss_category(repo, id, branch);
      cat.edit();
    }.bind(this, this.category_repository.id, k, this.category_repository.branch);
    a.appendChild(document.createTextNode(" (edit)"));
    li.appendChild(a);



    ul.appendChild(li);
  }

  this.win.content.appendChild(ul);

  var a = document.createElement("a");
  a.onclick = function(repo) {
    var form_def = {
      'id': {
        'type': 'text',
        'name': 'ID',
        'req': true
      }
    };

    new editor({
      form_def: form_def,
      data: {},
      title: "Create new category",
      onsave: function(repo, data) {
        var cat = new get_mapcss_category(repo.id, data.id, repo.branch);
        cat.edit();
      }.bind(this, repo)
    });

  }.bind(this, this.category_repository);
  a.appendChild(document.createTextNode("New category"));
  this.win.content.appendChild(a);
}

CategoryRepositoryBrowser.prototype.close = function() {
  this.category_repository.onload = null;
}

function category_repository_browser_open(id) {
  new CategoryRepositoryBrowser(id);
}
