function CategoryRepositoryBrowser(id, branch) {
  this.win = new win({
    title: id ? "Repository \"" + id + "\"" : "Repository overview",
    class: "category_repository_browser"
  });
  this.win.onclose = this.close.bind(this);

  if(!id) {
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
        title: "Create new category repository",
        onsave: function(data) {
          ajax_json('category_repository_create', data, function(id) {
            category_repository_browser_open(id);
            this.win.close();
          }.bind(this, id));
        }.bind(this)
      });

    }.bind(this, this.category_repository);
    a.appendChild(document.createTextNode("New category repository"));
    this.win.content.appendChild(a);

    var h = document.createElement("h4");
    h.appendChild(document.createTextNode("Repositories"));
    this.win.content.appendChild(h);

    var div = document.createElement("div");
    div.innerHTML = "Loading ...";
    this.win.content.appendChild(div);
    ajax_json('category_repository_list', {}, this.show_list.bind(this, div));
  }
  else {
    this.win.content.innerHTML = "Loading ...";

    get_category_repository(id, function(ob) {
      this.category_repository = ob;
      this.show();
      this.category_repository.on('load', this.show.bind(this), this);
    }.bind(this));
  }
}

CategoryRepositoryBrowser.prototype.show_list = function(div, data) {
  dom_clean(div);

  var ul = document.createElement("ul");
  for(var id in data) {
    var repo = data[id];
    repo.title = id;

    var li = document.createElement("li");
    li.innerHTML = twig_render_custom("<a action='show'>{{ id }}</a>", repo);
    link_actions(li, {
      'show': function(id) {
        category_repository_browser_open(id);
        this.win.close();
      }.bind(this, id)
    });

    ul.appendChild(li);
  }

  div.appendChild(ul);
}

CategoryRepositoryBrowser.prototype.show = function() {
  this.category_repository.get_categories(this.show_1.bind(this));
}

CategoryRepositoryBrowser.prototype.get_category_from_data = function(id) {
  var id_path = id.split("/");
  var d = this.data;

  if(id_path[0] == this.id)
    return null;
  id_path.splice(0, 1);

  while(id_path.length) {
    if(!(id_path[0] in d.categories))
      return null;

    d = d.categories[id_path[0]];
    id_path.splice(0, 1);
  }

  return d;
}

CategoryRepositoryBrowser.prototype.show_1 = function(categories) {
  this.data = this.category_repository.data();

  twig_render_into(this.win.content, "category_repository_overview.html", this.data, function(dom) {
    link_actions(dom, {
      'add': function(id) {
          this.category_repository.get_category(id, function(cat) {
            if(cat)
              category_root.register_sub_category(cat);
            else
              alert("Can't create layer from category!");
          }.bind(this));
        }.bind(this),

      'edit': function(id) {
          this.category_repository.get_category(id, function(cat) {
            cat.edit();
          }.bind(this));
        }.bind(this),

      'new': function() {
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
          onsave: function(data) {
            get_mapcss_category(this.data.id + "/" + data.id, function(cat) {
              cat.edit();
            }.bind(this));
          }.bind(this)
        });

        this.win.close();
      }.bind(this)
    });
  }.bind(this));
}

CategoryRepositoryBrowser.prototype.close = function() {
  if(this.category_repository)
    this.category_repository.off(null, null, this);
}

function category_repository_browser_open(id) {
  new CategoryRepositoryBrowser(id);
}
