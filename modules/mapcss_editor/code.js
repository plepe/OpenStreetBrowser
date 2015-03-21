function mapcss_editor(id) {
  var title;

  this.id = id;

  if(id) {
    title = "Edit category '" + id + "'";
  }
  else {
    title = "New category";
  }

  this.win = new win({
    'title': title,
    'class': 'mapcss_editor'
  });

  if(this.id) {
    this.win.content.innerHTML = "Loading ...";
    ajax("mapcss_editor_load", { id: id }, null, function(data) {
      data = data.return_value;
      this.open(data);
    }.bind(this));
  }
  else {
    this.open(null);
  }
}

mapcss_editor.prototype.open = function(data) {
  var form_def = {};

  if(!this.id) {
    form_def['id'] = {
      'type': 'text',
      'name': "ID",
      'req': true
    };
  }

  form_def['content'] = {
    'type': 'textarea',
    'name': "MapCSS code",
    'req': true
  };

  dom_clean(this.win.content);

  this.form = new form('data', form_def);
  if(data)
    this.form.set_data(data);
  this.form.show(this.win.content);

  var div = document.createElement("div");
  this.win.content.appendChild(div);

  var input = document.createElement("input");
  input.type = "button";
  input.value = "Save";
  div.appendChild(input);
  input.onclick = this.save.bind(this);

  return true;
}

mapcss_editor.prototype.save = function() {
  if(this.form.errors()) {
    this.form.show_errors();
    alert("Please complete the form");

    return;
  }

  var param = JSON.stringify(this.form.get_data());

  ajax("mapcss_editor_save", { id: this.id }, param, this.save_callback.bind(this));

  return false;
}

mapcss_editor.prototype.save_callback = function(data) {
  data = data.return_value;

  if(typeof(data) != "object")
    alert("An error occured while saving.");
  else {
    if('error' in data)
      alert("An error occured while saving: " + data.error);
    else if('compile_log' in data)
      alert("Saved. Compile log:\n" + data['compile_log']);
    else
      alert("Saved.");
  }

  this.win.close();
}

function mapcss_editor_open(id) {
  new mapcss_editor(id);
}
