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
  this.update();
}

gps_toolbox_class.prototype.update=function(ob) {
  if(!this.show_lat) {
    dom_clean(this.content);

    var list=[];

    var t=document.createElement("table");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("latitude")+":");
    this.show_lat=dom_create_append(tr, "td");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("longitude")+":");
    this.show_lon=dom_create_append(tr, "td");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("altitude")+":");
    this.show_alt=dom_create_append(tr, "td");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("heading")+":");
    this.show_heading=dom_create_append(tr, "td");

    var tr=dom_create_append(t, "tr");
    var td=dom_create_append(tr, "td");
    dom_create_append_text(td, lang("speed")+":");
    this.show_speed=dom_create_append(tr, "td");

    list.push([0, t]);
    call_hooks("gps_toolbox_show", list);
    list=weight_sort(list);

    for(var i=0; i<list.length; i++) {
      this.content.appendChild(list[i]);
    }
  }

  if(!ob) {
    dom_clean(this.show_lat);
    dom_create_append_text(this.show_lat, "?");
    dom_clean(this.show_lon);
    dom_create_append_text(this.show_lon, "?");
    dom_clean(this.show_alt);
    dom_create_append_text(this.show_alt, "?");
    dom_clean(this.show_heading);
    dom_create_append_text(this.show_heading, "?");
    dom_clean(this.show_speed);
    dom_create_append_text(this.show_speed, "?");

    return;
  }

  dom_clean(this.show_lat);
  dom_create_append_text(this.show_lat, units_format_latitude(ob.pos));
  dom_clean(this.show_lon);
  dom_create_append_text(this.show_lon, units_format_longitude(ob.pos));
  dom_clean(this.show_alt);
  dom_create_append_text(this.show_alt, units_format_altitude(ob.coords.altitude, "m"));
  dom_clean(this.show_heading);
  dom_create_append_text(this.show_heading, units_format_heading(ob.coords.heading, "deg"));
  dom_clean(this.show_speed);
  dom_create_append_text(this.show_speed, units_format_speed(ob.coords.speed, "m/s"));
}

function gps_toolbox_init() {
  if(gps_object)
    gps_toolbox=new gps_toolbox_class();
}

register_hook("init", gps_toolbox_init);
