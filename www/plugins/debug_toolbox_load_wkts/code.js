function debug_toolbox_load_wkts_submit() {
  return true;
}

function debug_toolbox_load_wkts() {
  this.win=new win();

  this.form=dom_create_append(this.win.content, "form");
  this.form.action="javascript:debug_toolbox_load_wkts_submit()";
  this.form.onsubmit=this.submit.bind(this);

  dom_create_append_text(this.form, lang("debug_toolbox_load_wkts:info"));

  var textarea=dom_create_append(this.form, "textarea");
  textarea.cols=60;
  textarea.rows=15;
  textarea.name="wkts";

  var input=dom_create_append(this.form, "input");
  input.type="submit";
  input.value=lang("ok");
}

debug_toolbox_load_wkts.prototype.submit=function() {
  var list=this.form.wkts.value.split("\n");
  for(var i=0; i<list.length; i++) {
    var x=new postgis(list[i]);
    vector_layer.addFeatures(x.geo());
  }

  this.win.close();
}

function debug_toolbox_load_wkts_show() {
  new debug_toolbox_load_wkts();
}

function debug_toolbox_load_wkts_init() {
  var div=document.createElement("div");

  var a=dom_create_append(div, "a");
  a.href="javascript:debug_toolbox_load_wkts_show()";
  dom_create_append_text(a, lang("debug_toolbox_load_wkts:load"));

  debug_toolbox_register({
    weight: 5,
    dom: div
  });
}

register_hook("init", debug_toolbox_load_wkts_init);
