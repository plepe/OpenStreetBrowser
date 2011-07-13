var search_toolbox;
var search_list;
var search_param;
var search_shown;
var search_form;

function search_init() {
  search_toolbox=new toolbox({
    icon: "plugins/search/icon.png",
    icon_title: "search",
    callback_activate: search_toolbox_activate,
    weight: -4
  });
  register_toolbox(search_toolbox);

  var content=dom_create_append(search_toolbox.content, "div");
  content.className="search";
  
  search_form=dom_create_append(content, "form");
  search_form.name="osb_search_form_name";
  search_form.id="osb_search_form";
  search_form.action="javascript:search()";

  var input=dom_create_append(search_form, "input");
  input.name="osb_search";
  input.id="search";
  input.value=lang("search_field");
  input.onfocus=search_focus;
  input.onkeyup=search_brush;
  input.onblur=search_onblur;
  input.title=lang("search_tip");

  var img=dom_create_append(search_form, "img");
  img.name="brush";
  img.src="plugins/search/brush.png";
  img.title=lang("search_clear");
  img.id="brush";
  img.className="invisible";
  img.onclick=search_clear;
  img.onmousedown=search_brush_mousedown;

  if(toolbox_manager.current_active==-1) {
    search_toolbox.activate();
  }
}

function search_toolbox_activate() {
  alert(toolbox_manager.current_active);
}

function search_brush_mousedown(event) {
  if(event.preventDefault)
    event.preventDefault();
}
register_hook("init", search_init);


function search_focus() {
  if(search_form.osb_search.value==lang("search_field")) {
    search_form.osb_search.value='';
  }
  else if((search_form.osb_search.value!="")) {
    search_form.brush.className = 'visible';
  }
}

function search_clear() {
  search_form.osb_search.value='';
  search_form.brush.className = 'invisible';
  search_form.osb_search.focus();
}

function search_brush(event) {
  if((search_form.osb_search.value!="")) {
    search_form.brush.className = 'visible';
  }
  else {
    search_form.brush.className = 'invisible';
  }
  if(event.which==27) {
    search_clear();
  }
}

function search_onblur() {
  if((search_form.osb_search.value=='\0')||(search_form.osb_search.value=="")) {
    search_form.osb_search.value=lang_str["search_field"];
    search_form.brush.className = 'invisible';
  }
}

function search() {
  var search_form=document.getElementById("osb_search_form");

  var details_content=document.getElementById("details_content");
  var details=document.getElementById("details");
  location.hash="#search_"+search_form.elements.osb_search.value;
}

function real_search(value, param) {
  if(!param)
    param={};

  param.value=value;

  var x=map.calculateBounds().transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
  var viewbox=[ x.left, x.top, x.right, x.bottom ];
  param.viewbox=viewbox.join(",");

  search_param=param;
  search_shown=[];

  dom_clean(details_content);

  details_content.className="info_loading";

  var d=dom_create_append(details_content, "div");
  d.className="zoombuttons";
  d.innerHTML="<a class='zoom' href='#'>"+lang("info_back")+"</a>";

  var search_content=dom_create_append(details_content, "div");
  search_content.className="search_content";

  var d=dom_create_append(details_content, "div");
  d.innerHTML="<i>"+lang("search_nominatim")+" <a href='http://nominatim.openstreetmap.org/'>Nominatim</a></i>";

  search_list=new list(search_content, [ null ], search_more );
}

function search_result(data) {
  var elements=[];

  if(!data.return_value) {
    alert("no return on search request");
  }

  for(var i=0; i<data.return_value.length; i++) {
    var el={};
    var ob=new osm_object(data.return_value[i]);

    search_shown.push(ob.tags.get("nominatim_id"));

    el.name=ob.tags.get("name");
    el.href="#"+ob.id;

    if(ob.tags.get("#geo:center")) {
      el.highlight=ob.tags.get("#geo:center");
    }
    else if(ob.tags.get("lat")) {
      var poi=
        new OpenLayers.Geometry.Point(ob.tags.get("lon"), ob.tags.get("lat"));
      poi=poi.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
      el.highlight=poi.toString();
    }

    elements.push(el);
  }

  // add a null to indicate "more"
  if(elements.length>0)
    elements.push(null);

  search_list.recv(elements);
}

function search_more(param1, param2) {
  search_param.shown=search_shown.join(",");
  ajax("search", search_param, search_result);

  return;
}
