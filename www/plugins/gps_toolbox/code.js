var gps_toolbox;

function gps_toolbox_class(options) {
  if(!options)
    options={};
  options.icon="plugins/gps_toolbox/icon.png";
  options.icon_title=lang("gps_toolbox:name");
  options.weight=5;

  this.inheritFrom=toolbox;
  this.inheritFrom(options);

  register_toolbox(this);

  register_hook("gps_update", this.update.bind(this));
}

gps_toolbox_class.prototype.update=function(ob) {
  if(!this.show_lat) {
    dom_clean(this.content);

    var t=dom_create_append(this.content, "table");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("latitude")+":");
    this.show_lat=dom_create_append(tr, "td");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("longitude")+":");
    this.show_lon=dom_create_append(tr, "td");
  }

  dom_clean(this.show_lat);
  dom_create_append_text(this.show_lat, ob.pos.lat);
  dom_clean(this.show_lon);
  dom_create_append_text(this.show_lon, ob.pos.lon);
}

function gps_toolbox_init() {
  gps_toolbox=new gps_toolbox_class();
}

register_hook("init", gps_toolbox_init);
