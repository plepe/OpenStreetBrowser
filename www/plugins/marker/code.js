var marker_list={};
var marker_drag_control;

var marker_style={
    externalGraphic: 'plugins/marker/marker.png',
    graphicWidth: 21,
    graphicHeight: 25,
    graphicXOffset: -11,
    graphicYOffset: -25
  };
var marker_style_selected={
    externalGraphic: 'plugins/marker/marker_selected.png',
    graphicWidth: 21,
    graphicHeight: 25,
    graphicXOffset: -11,
    graphicYOffset: -25
  };


function marker_update(new_hash) {
  // no mlat / mlon in new_hash
  if(!(new_hash.mlat)||(!new_hash.mlon))
    return;

  // get parts of mlat- and mlon-parameters
  var mlats=new_hash.mlat.split(/,/);
  var mlons=new_hash.mlon.split(/,/);

  for(var i=0; i<mlats.length; i++) {
    // if we already set this marker, ignore
    if(marker_list[mlons[i]+"|"+mlats[i]])
      continue;

    // add marker
    marker_add(mlons[i], mlats[i]);
  }
}

function marker_permalink(permalink) {
  // initalize empty arrays
  var mlats=[];
  var mlons=[];

  // for each element in marker_list
  for(var i in marker_list) {
    var pos=i.split(/\|/);

    // push them to the arrays
    mlons.push(pos[0]);
    mlats.push(pos[1]);
  }

  // if we don't have any markers in the list, return
  if(!mlats.length)
    return ;

  // save in permalink
  permalink.mlat=mlats.join(",");
  permalink.mlon=mlons.join(",");
}

function marker(lon, lat) {
  // finish_drag
  this.finish_drag=function(pos) {
    // calculate lonlat of new position
    var lonlat=pos.transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

    if(lonlat.x) {
      lonlat.lon=lonlat.x;
      lonlat.lat=lonlat.y;
    }

    // save new position to marker_list
    delete marker_list[this.lon+"|"+this.lat];
    this.lon=lonlat.lon;
    this.lat=lonlat.lat;
    marker_list[this.lon+"|"+this.lat]=this;

    // update permalink
    update_permalink();

    this.update_details();
  }

  // next_drag
  this.next_drag=function(pos) {
    this.finish_drag(pos);
  }

  // object_select
  this.object_select=function(pos) {
    this.feature.style=marker_style_selected;
    drag_layer.drawFeature(this.feature);

    this.show_details();
  }

  // object_unselect
  this.object_unselect=function(pos) {
    this.feature.style=marker_style;
    drag_layer.drawFeature(this.feature);
  }

  // show_details
  this.show_details=function() {
    var ret="";
    var info_content=document.getElementById("details_content");

    dom_clean(info_content);
    var div=dom_create_append(info_content, "div");
    div.className="object";

    var head=dom_create_append(div, "h1");
    dom_create_append_text(head, t("marker", 1));

    var div1=dom_create_append(div, "div");
    div1.className="obj_actions";
    div1.innerHTML="<a class='zoom' href='#' onClick='redraw()'>"+t("info_back")+"</a><br>\n";

    var head=dom_create_append(div, "h2");
    dom_create_append_text(head, t("head:location", 1));
    this.details_location=dom_create_append(div, "ul");
    this.update_details();

    var head=dom_create_append(div, "h2");
    dom_create_append_text(head, t("head:action"));
    var ul=dom_create_append(div, "ul");
    var li=dom_create_append(ul, "li");
    var a=dom_create_append(li, "a");
    dom_create_append_text(a, t("marker_action:remove"));
    a.onclick=this.remove.bind(this);
  }

  // update_details
  this.update_details=function() {
    if(!this.details_location)
      return;

    dom_clean(this.details_location);
    var li=dom_create_append(this.details_location, "li");
    dom_create_append_text(li, t("longitude", 1)+": "+this.lon.toFixed(5));
    var li=dom_create_append(this.details_location, "li");
    dom_create_append_text(li, t("latitude", 1)+": "+this.lat.toFixed(5));
  }

  // remove
  this.remove=function() {
    delete marker_list[this.lon+"|"+this.lat];
    drag_layer.unselect(this.feature);
    drag_layer.removeFeatures([this.feature]);
    update_permalink();
    redraw();
  }

  // constructor
  this.lon=parseFloat(lon);
  this.lat=parseFloat(lat);

  // create the new marker
  var pos = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
  var geo = new OpenLayers.Geometry.Point(pos.lon, pos.lat);
  this.feature = new OpenLayers.Feature.Vector(geo, 0, marker_style);
  drag_layer.addFeatures([this.feature]);
  this.feature.ob=this;

  // save marker in marker_list
  marker_list[lon+"|"+lat]=this;

  // force an update of the permalink
  update_permalink();
}

function marker_add(lon, lat) {
  return new marker(lon, lat);
}

function marker_add_context(pos) {
  // add a marker on the pos
  var m=marker_add(pos.lon, pos.lat);

  // select marker
  drag_layer.unselectAll();
  drag_layer.select(m.feature);
}

function marker_init() {
  contextmenu_add("plugins/marker/icon.png", "add marker", marker_add_context);
}

register_hook("get_permalink", marker_permalink);
register_hook("hash_changed", marker_update);
register_hook("init", marker_init);
