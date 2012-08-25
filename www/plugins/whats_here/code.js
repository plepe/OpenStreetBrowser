var whats_here_style={
    externalGraphic: 'plugins/whats_here/whats_here.png',
    graphicWidth: 19,
    graphicHeight: 19,
    graphicXOffset: -10,
    graphicYOffset: -10
  };

function whats_here(lonlat) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="whats_here";

  this.geo_center=function() {
    return [ this.feature ];
  }

  this.request=function() {
    if(category_request)
      category_request.abort();

    var cat=category_root.visible_list();
    cat=category_list_to_string(cat);

    var pos = deep_clone(lonlat);
    pos.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());

    var geo=new OpenLayers.Geometry.Point(pos.lon, pos.lat);
    this.feature = new OpenLayers.Feature.Vector(geo, 0, whats_here_style);
    vector_layer.addFeatures([this.feature]);

    category_request=ajax("whats_here_find", { "zoom": map.zoom, "lon": pos.lon, "lat": pos.lat, "categories": cat }, this.request_callback.bind(this));
  }

  this.name=function() {
    return lang("whats_here:name", 0, lonlat.lat, lonlat.lon);
  }

  this.info=function(chapters) {
    this.view_div=document.createElement("div");
    this.view_div.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

    chapters.push({ head: "whats_here", content: this.view_div });
  }

  // request_callback
  this.request_callback=function(response) {
    var data=response.responseXML;
    category_request=null;

    if(!data) {
      alert("no data\n"+response.responseText);
      return;
    }

    dom_clean(this.view_div);
    var ul=dom_create_append(this.view_div, "ul");

    var result=data.getElementsByTagName("list");
    if(!result.length) {
      alert("No result!");
      return;
    }
    result=result[0];

    var match=result.firstChild;
    while(match) {
      var osm=new osm_object(match);

      ul.appendChild(osm.list_element());

      match=match.nextSibling;
    }

    return;
  }

  // info_hide
  this.info_hide=function() {
    vector_layer.removeFeatures([this.feature]);
  }

  // constructor
  this.id="whats_here="+lonlat.lon+","+lonlat.lat;
  this.request();
}

function whats_here_view_click(event, pos) {
  // if contextmenu listens for left mouse button ignore
  if(options_get("contextmenu_mouse_button")!="right")
    return;

  var lonlat = pos.transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
  set_url({ obj: "whats_here="+lonlat.lon+","+lonlat.lat });
}

function whats_here_search_object(objects, str) {
  var erg;

  if(erg=str.match(/^whats_here=([\-0-9\.]*),([\-0-9\.]*)$/)) {
    var lonlat = new OpenLayers.LonLat(erg[1], erg[2]);

    var ob=new whats_here(lonlat);
    objects.push(ob);
  }
}

function whats_here_contextmenu(pos) {
  set_url({ obj: "whats_here="+pos.lon+","+pos.lat });
}

function whats_here_init() {
  if(plugins_loaded("contextmenu")) {
    contextmenu_add("plugins/whats_here/icon.png", lang("whats_here:contextmenu"), whats_here_contextmenu, { weight: -10 });
  }
}

register_hook("init", whats_here_init);
register_hook("view_click", whats_here_view_click);
register_hook("search_object", whats_here_search_object);
