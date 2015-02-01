var marker_list=[];
var marker_drag_control;
var marker_highest_id=0;

var marker_style = new ol.style.Style({
  image: new ol.style.Icon({
    src: modulekit_file("marker", "marker.png"),
    anchor: [ 0.5, 1 ]
  }),
  opacity: 1
});
var marker_style_selected = new ol.style.Style({
  image: new ol.style.Icon({
    src: modulekit_file("marker", "marker_selected.png"),
    anchor: [ 0.5, 1 ]
  }),
  opacity: 1
});

function marker_update(new_hash) {
  // no mlat / mlon in new_hash
  if(!(new_hash.mlat)||(!new_hash.mlon))
    return;

  // get parts of mlat- and mlon-parameters
  var mlats=new_hash.mlat.split(/,|%2C/);
  var mlons=new_hash.mlon.split(/,|%2C/);

  for(var i=0; i<mlats.length; i++) {
    // if we already set this marker, ignore
    var found=0;
    for(var j=0; j<marker_list; j++) {
      if((marker_list[i].lon==mlons[i])&&
         (marker_list[i].lat==mlats[i]))
        found=1;
    }
    if(found)
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
  for(var i=0; i<marker_list.length; i++) {
    // push them to the arrays
    mlons.push(marker_list[i].lon.toFixed(5));
    mlats.push(marker_list[i].lat.toFixed(5));
  }

  // if we don't have any markers in the list, return
  if(!mlats.length)
    return ;

  // save in permalink
  permalink.mlat=mlats.join(",");
  permalink.mlon=mlons.join(",");
}

function marker(lon, lat) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="marker";

  // geo
  this.geo=function() {
    return [this.feature];
  }

  // geo_center
  this.geo_center=function() {
    return [this.feature];
  }

  // next_drag
  this.next_drag=function(pos) {
    // calculate lonlat of new position
    var lonlat=pos.transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

    if(lonlat.x) {
      lonlat.lon=lonlat.x;
      lonlat.lat=lonlat.y;
    }

    // set new coordinates
    this.lon=lonlat.lon;
    this.lat=lonlat.lat;

    // update permalink
    update_permalink();

    // Inform other objects
    call_hooks("marker_moved", this);

    this.update_details();
  }

  // finish_drag
  this.finish_drag=function(pos) {
    // do as if we just moved the object
    this.next_drag(pos);

    // no update id and set a new URL
    this.id="marker_"+this.lon.toFixed(5)+","+this.lat.toFixed(5);
    set_url({ obj: this.id });
  }

  // object_select
  this.object_select=function(pos) {
    this.feature.setStyle(marker_style_selected);

    set_url({ obj: this.id });
  }

  // object_unselect
  this.object_unselect=function(pos) {
    if(this.removed)
      return;

    this.feature.setStyle(marker_style);
  }

  this.info_hide=this.object_unselect;

  // update_details
  this.update_details=function() {
    if(!this.details_location)
      return;

    dom_clean(this.details_location);
    var li=dom_create_append(this.details_location, "li");
    dom_create_append_text(li, lang("longitude")+": "+this.lon.toFixed(5));
    var li=dom_create_append(this.details_location, "li");
    dom_create_append_text(li, lang("latitude")+": "+this.lat.toFixed(5));
  }

  // name
  this.name=function() {
    return lang("marker:name")+" "+
      this.lat.toFixed(5)+"/"+
      this.lon.toFixed(5);
  }

  // info
  this.info=function(chapters) {
    this.details_location=document.createElement("ul");
    this.update_details();
    chapters.push({
      head: "location",
      weight: -1,
      content: this.details_location
    });

    // create link
    var a=document.createElement("a");
    dom_create_append_text(a, lang("marker:action_remove"));
    a.onclick=this.remove.bind(this);

    // insert to chapters
    chapters.push({
      head: "actions",
      weight: 1,
      content: [ a ]
    });

    // show selected marker
    this.object_select();
  }

  // write_list
  this.write_list=function(ul) {
    var ret={};

    ret.icon_url=modulekit_file("marker", "icon.png");
    ret.name=this.name();
    ret.href=url({ obj: this.id });
    ret.highlight=new ol.format.WKT().writeFeature(this.feature);
    ret.highlight_center=new ol.format.WKT().writeFeature(this.feature);

    return ret;
  }

  // geo_center
  this.geo_center=function() {
    return [ this.feature ];
  }

  // remove
  this.remove=function() {
    for(var i=0; i<marker_list.length; i++) {
      if(marker_list[i]==this)
        marker_list=marker_list.slice(0, i).concat(marker_list.slice(i+1));
    }
    drag_layer.unselect(this.feature);
    drag_layer.remove_feature(this.feature);
    this.removed=true;
    set_url({}, true);
    update_permalink();
    redraw();

    // Inform other objects
    call_hooks("marker_removed", this);

    return false;
  }

  // constructor
  this.id="marker"; // TODO: should be unique
  this.type="marker";

  this.lon=parseFloat(lon);
  this.lat=parseFloat(lat);
  this.id="marker_"+this.lon.toFixed(5)+","+this.lat.toFixed(5);

  // create the new marker
  var pos = ol.proj.transform([this.lon, this.lat], "EPSG:4326", "EPSG:3857");
  var geo = new ol.geom.Point(pos);
  this.feature = new ol.Feature({
    geometry: geo,
    name: 'Marker',
  });
  this.feature.setStyle(marker_style);
  drag_layer.add_feature(this.feature);
  this.feature.ob=this;
  drag_layer.select(this.feature);

  // save marker in marker_list
  marker_list.push(this);

  // force an update of the permalink
  update_permalink();

  // Inform other objects
  call_hooks("marker_created", this);
}

function marker_add(lon, lat) {
  var id="marker_"+parseFloat(lon).toFixed(5)+","+parseFloat(lat).toFixed(5);

  for(var i=0; i<marker_list.length; i++)
    if(marker_list[i].id==id)
      return marker_list[i];

  return new marker(lon, lat);
}

function marker_add_context(pos) {
  // add a marker on the pos
  var marker=marker_add(pos[0], pos[1]);

  // redirect to marker-page
  set_url({ obj: marker.id });
}

function marker_search_object(ret, id) {
  for(var i=0; i<marker_list.length; i++) {
    if(marker_list[i].id==id)
      ret.push(marker_list[i]);
  }

  var m;
  if((ret.length==0)&&
     (m=id.match("^marker_(\-?[0-9]+\.[0-9]+),(\-?[0-9]+\.[0-9]+)$"))) {
    ret.push(marker_add(m[1], m[2]));
  }
}

function marker_place(pos) {
  if(!pos.lon)
    pos=pos.lonlat();

  // place marker
  var marker=marker_add(pos.lon, pos.lat);

  // redirect to marker-page
  set_url({ obj: marker.id });

  return false;
}

function marker_info(chapters, ob) {
  if(ob.geo_center()&&(ob.type!="marker")) {
    var a=document.createElement("a");
    a.onclick=marker_place.bind(this, ob);
    dom_create_append_text(a, lang("marker:place"));

    var entry={
      head: 'actions',
      weight: 9,
      content: [ a ]
    };

    chapters.push(entry);
  }
}

function marker_init() {
  if(modulekit_loaded("contextmenu")) {
    contextmenu_add(modulekit_file("marker", "icon.png"), lang("marker:add_marker"), marker_add_context);
  }
}

register_hook("get_permalink", marker_permalink);
register_hook("hash_changed", marker_update);
register_hook("init", marker_init);
register_hook("search_object", marker_search_object);
register_hook("info", marker_info);
