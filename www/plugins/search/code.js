var search_toolbox;
var search_list;
var search_param;
var search_shown;

function search_init() {
  search_toolbox=new toolbox({
    icon: "plugins/search/icon.png",
    icon_title: "search",
    weight: -4,
  });
  register_toolbox(search_toolbox);
  var text = "<form name='osb_search_form_name' id='osb_search_form' action='javascript:search()'><input name='osb_search' id='search' value='"+lang("search_field")+"' onFocus='search_focus(this)' onkeyup='search_brush(this,event)' onblur='search_onblur(this)' 'title='"+lang("search_tip")+"'/><img name='brush' src='plugins/search/brush.png' border='0' alt='' title='"+lang("search_clear")+"' style='position:absolute; right:3px; visibility:hidden; cursor:pointer;' onclick='search_clear(document.osb_search_form_name.osb_search)' onmousedown='if (event.preventDefault) event.preventDefault()'></form>";
  search_toolbox.content.innerHTML=text;
  if(toolbox_manager.current_active==-1) {
    search_toolbox.activate();
  }
}

register_hook("init", search_init);


function search_focus(ob) {
  if(ob.value==lang("search_field")) {
    ob.value='';
  }
  else if((ob.value!="")) {
    document.getElementById("osb_search_form").brush.style.visibility = 'visible';
  }
}

function search_clear(ob) {
  ob.value='';
  document.getElementById("osb_search_form").brush.style.visibility = 'hidden';
  ob.focus();
}

function search_brush(ob,e) {
  if((ob.value!="")) {
    document.getElementById("osb_search_form").brush.style.visibility = 'visible';
  }
  else {
    document.getElementById("osb_search_form").brush.style.visibility = 'hidden';
  }
  if(e.which==27) {
    search_clear(ob);
  }
}

function search_onblur(ob) {
  if((ob.value=='\0')||(ob.value=="")) {
    ob.value=lang_str["search_field"];
    document.getElementById("osb_search_form").brush.style.visibility = 'hidden';
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
