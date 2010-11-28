function whats_here(lonlat) {
  this.request=function() {
    if(category_request)
      category_request.abort();

    var cat=category_root.visible_list();
    cat=category_list_to_string(cat);

    var pos = new clone(lonlat);
    pos.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    category_request=ajax("whats_here_find", { "zoom": map.zoom, "lon": pos.lon, "lat": pos.lat, "categories": cat }, this.request_callback.bind(this));
  }

  this.name=function() {
    return lang("Position: ")+lonlat.lat.toFixed(5)+"/"+lonlat.lon.toFixed(5);
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
      var li=dom_create_append(ul, "li");
      var osm=new osm_object(match);
      dom_create_append_text(li, osm.name());

      match=match.nextSibling;
    }

    return;
  }

  // constructor
  this.request();
}

function whats_here_view_click(event, pos) {
  var lonlat = pos.transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
  location.hash="#whats_here="+lonlat.lon+","+lonlat.lat;
}

function whats_here_search_object(objects, str) {
  var erg;

  if(erg=str.match(/^whats_here=([\-0-9\.]*),([\-0-9\.]*)$/)) {
    var lonlat = new OpenLayers.LonLat(erg[1], erg[2]);

    var ob=new whats_here(lonlat);
    objects.push(ob);
  }
}

register_hook("view_click", whats_here_view_click);
register_hook("search_object", whats_here_search_object);
