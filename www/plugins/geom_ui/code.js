var geom_ui_current;
var geom_ui_color_list=[ '#009f00', '#9f0000', '#008f8f', '#00009f', '#8f008f', '#8f8f00', '#009f3f', '#003f9f', '#3f009f', '#9f003f', '#3f9f00', '#9f3f00' ];

function geom_ui(info, ob) {
  this.ob=ob;

  // create geom chapter in info-box
  var chapter=document.createElement("div");
  info.push({
    head: lang("geom_ui:name"),
    weight: 5,
    content: chapter
  });

  this.inputs={};

  this.form=dom_create_append(chapter, "form");
  this.form.action="javascript:geom_ui_submit()";
  this.form.onsubmit=this.refresh.bind(this);

  var d=dom_create_append(this.form, "div");
  dom_create_append_text(d, lang("geom_ui:function")+": ");
  this.inputs.fun=dom_create_append(d, "select");
  this.inputs.fun.onchange=this.fun_change.bind(this);
  var opt=dom_create_append(this.inputs.fun, "option");
  opt.value="";
  dom_create_append_text(opt, "choose ...");

  for(var i in geom_funs) {
    var opt=dom_create_append(this.inputs.fun, "option");
    opt.value=i;
    dom_create_append_text(opt, i);
  }


  this.inputs.params=dom_create_append(this.form, "div");

  this.inputs.submit=dom_create_append(this.form, "input");
  this.inputs.submit.type="submit";
  this.inputs.submit.value=lang("ok");

  this.div=dom_create_append(chapter, "div");

  this.load();
}

geom_ui.prototype.fun_change=function() {
  dom_clean(this.inputs.params);
  this.params={};

  var def=geom_funs[this.inputs.fun.value];
  if(!def)
    return;

  for(var i in def) {
    var d=dom_create_append(this.inputs.params, "div");

    dom_create_append_text(d, i+": ");

    switch(def[i][0]) {
      case "float":
      case "text":
      case "int":
	var input=dom_create_append(d, "input");
	this.params[i]=input;
	input.name=i;
	if(def[i].length>1)
	  input.value=def[i][1];
        break;
      case "bool":
	var input=dom_create_append(d, "input");
	this.params[i]=input;
	input.type="checkbox";
	if(def[i].length>1)
	  input.checked=(def[i][1]!="false")&&(def[i][1]);
      default:
    }
  }
}

geom_ui.prototype.get_params=function() {
  var ret={};
  var def=geom_funs[this.inputs.fun.value];
  if(!def)
    return;

  for(var i in def) {
    var d=dom_create_append(this.inputs.params, "div");

    switch(def[i][0]) {
      case "float":
      case "text":
      case "int":
	ret[i]=this.params[i].value;
	break;
      case "bool":
	ret[i]=this.params[i].checked?"true":"false";
	break;
    }
  }

  return ret;
}

geom_ui.prototype.load=function() {
  // call ajax-function
  var param={};
  param.fun=this.inputs.fun.value;
  if(!param.fun)
    return;
  param.id=this.ob.id;
  param.zoom=map.zoom;
  param.param=this.get_params();

  ajax("geom", param, this.load_callback.bind(this));
}

geom_ui.prototype.load_callback=function(result) {
  var debug_colors={};
  var value=result.return_value;

  this.debug_features=[];
  var debug_colors={ 'result': '#ff0000' };
  debug_color_index=0;

  // show additional #geom-tags
  var ul=dom_create_append(this.div, "ul");
  var ks=keys(value.tags);
  ks.sort();
  for(var ki=0; ki<ks.length; ki++) {
    var i=ks[ki];
    var k;
    if((k=i.match(/^#geom:([^:]*):/))&&(!debug_colors[k[1]])) {
      debug_colors[k[1]]=geom_ui_color_list[debug_color_index++];
    }

    if(k=i.match(/^#geom:([^:]*):geo/)) {
      var geo=new postgis(value.tags[i]);
      var features=geo.geo();
      for(var j=0; j<features.length; j++) {
	features[j].style= { pointRadius: 3, fillColor: debug_colors[k[1]], strokeColor: debug_colors[k[1]], stroke: true, strokeWidth: 1, strokeOpacity: 1, fillOpacity: 0.2 };
      }
      vector_layer.addFeatures(features);
      this.debug_features.push(features);
    }
    else if(k=i.match(/^#geom:(([^:]*):.*)/)) {
      var li=dom_create_append(ul, "li");
      li.style.color=debug_colors[k[2]];
      dom_create_append_text(li, k[1]+": "+value.tags[i]);
    }
  }

  // show modified geometry
  var geo=new postgis(value.way);
  this.features=geo.geo();
  this.features[0].style= { pointRadius: 3, fillColor: "#ff0000", strokeColor: "#ff0000", stroke: true, strokeWidth: 1 };
  vector_layer.addFeatures(this.features);

}

geom_ui.prototype.refresh=function() {
  this.hide();
  this.load();
}

geom_ui.prototype.hide=function() {
  if(this.features) {
    vector_layer.removeFeatures(this.features);
    delete(this.features);
  }
  if(this.debug_features) {
    for(var i=0; i<this.debug_features.length; i++) {
      vector_layer.removeFeatures(this.debug_features[i]);
    }
    delete(this.debug_features);
  }
  dom_clean(this.div);
}

function geom_ui_info_hide() {
  if(geom_ui_current) {
    geom_ui_current.hide();
    delete(geom_ui_current);
  }
}

function geom_ui_info_show(info, ob) {
  geom_ui_current=new geom_ui(info, ob);
}

function geom_ui_submit() {
}

register_hook("info", geom_ui_info_show);
register_hook("info_hide", geom_ui_info_hide);
