function CategoryRepositoryBrowser(id, branch) {
  this.win = new win({
    title: "Repository \"" + id + "\"",
    class: "category_repository_browser"
  });
  this.win.content.innerHTML = "Loading ...";

  this.category_repository = get_category_repository(id, branch);
  this.category_repository.data(this.show.bind(this));
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
}

function category_repository_browser_open(id) {
  new CategoryRepositoryBrowser(id);
}
