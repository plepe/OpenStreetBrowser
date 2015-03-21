function mapcss_editor(id) {
  var form_def = {};
  var title;

  this.id = id;

  if(id) {
    title = "Edit category '" + id + "'";
  }
  else {
    title = "New category";
    form_def['id'] = {
      'type': 'text',
      'name': "ID",
      'req': true
    };
  }

  this.win = new win({
    'title': title,
    'class': 'mapcss_editor'
  });

  form_def['content'] = {
    'type': 'textarea',
    'name': "MapCSS code",
    'req': true
  };

  this.form = new form('data', form_def);
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

  if(data === true)
    alert("Saved.");
  else {
    if('error' in data)
      alert("An error occured while saving: " + data.error);
    else
      alert("An error occured while saving.");
  }

  this.win.close();
}

function mapcss_editor_open(id) {
  new mapcss_editor(id);
}
