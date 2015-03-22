function CategoryRepositoryBrowser(id, branch) {
  this.win = new win({
    title: "Repository \"" + id + "\"",
    class: "category_repository_browser"
  });
  this.win.content.innerHTML = "Loading ...";

  this.category_repository = new CategoryRepository(id, branch);
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

    var a = document.createElement("a");
    a.onclick = function(repo, id, branch) {
      new mapcss_editor(repo, id, branch);
    }.bind(this, this.category_repository.id, k, this.category_repository.branch);

    var li = document.createElement("li");
    a.appendChild(document.createTextNode(k));
    li.appendChild(a);

    ul.appendChild(li);
  }

  this.win.content.appendChild(ul);
}

function category_repository_browser_open(id) {
  new CategoryRepositoryBrowser(id);
}
