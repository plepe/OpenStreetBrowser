/**
 * options:
 * - title: window title
 * - class: window class
 * - onsave: Function to be called when save is called (and form is complete)
 * - oncancel: Function to be called when cancel is called
 * - form_def: form definition
 * - form_options: form options
 * - data: data (optional) - you can also call set_data() later-on
 */
function editor(options) {
  this.options = options;
  this.shown = false;

  this.win = new win({
    'title': this.options.title,
    'class': this.options['class'] || 'editor'
  });

  this.win.content.innerHTML = lang('loading');

  this.form = new form('data', this.options.form_def, this.options.form_options);

  if(this.options.data) {
    this.form.set_data(this.options.data);
    this.show_form();
  }
}

editor.prototype.set_data = function(data) {
  this.form.set_data(data);

  if(!this.shown)
    this.show_form();
}

editor.prototype.show_form = function() {
  dom_clean(this.win.content);

  this.form_el = document.createElement("form");
  this.form_el.onsubmit = this.save.bind(this);
  this.win.content.appendChild(this.form_el);

  this.form.show(this.form_el);
  this.shown = true;

  var input = document.createElement("input");
  input.type = "submit";
  input.value = lang('save');
  this.form_el.appendChild(input);

  var input = document.createElement("input");
  input.type = "button";
  input.value = lang('cancel');
  input.onclick = this.cancel.bind(this);
  this.form_el.appendChild(input);
}

editor.prototype.save = function() {
  this.form.show_errors();

  if(this.form.errors()) {
    alert(lang('editor:errors'));
    return false;
  }

  this.options.onsave(this.form.get_data());

  this.win.close();

  return false;
}

editor.prototype.cancel = function() {
  if(this.options.oncancel)
    this.options.oncancel(this.form.get_data());

  this.win.close();
}
