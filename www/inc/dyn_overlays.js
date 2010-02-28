var dyn_overlay={};
var dyn_overlay_imgs={};

function dyn_overlay_show(cat, match_ob) {
  var geo=match_ob.tags.get("geo:center");
  var icon=match_ob.tags.get("icon");
  var id=match_ob.id;
  var category=cat.id;
  var importance=match_ob.tags.get("importance");

  if(!geo)
    return;
  if(match_ob.dyn_overlay_vector) {
    dyn_overlay[category][importance].vectors.push(match_ob.dyn_overlay_vector);
    return;
  }

  var p=new postgis(geo).geo();

  if(!dyn_overlay[category])
    dyn_overlay[category]={ current_features: [] };

  if(!dyn_overlay[category][importance])
    dyn_overlay[category][importance]={ vectors: [], ids: {} };

  if(dyn_overlay[category][importance].ids[id])
    return;

  if(!dyn_overlay_imgs[icon]) {
    var newImg = new Image();
    newImg.src = icon;
    dyn_overlay_imgs[icon]={ width: newImg.width, height: newImg.height };
    delete newImg;
  }

  var f=new OpenLayers.Feature.Vector(p[0], 0, {
    strokeWidth: 4,
    strokeColor: "black",
    externalGraphic: icon,
    graphicWidth: dyn_overlay_imgs[icon].width,
    graphicHeight: dyn_overlay_imgs[icon].height,
    graphicXOffset: -(dyn_overlay_imgs[icon].width+1)/2,
    graphicYOffset: -(dyn_overlay_imgs[icon].height+1)/2,
    fill: "none"
  });
  f.match_ob=match_ob;
  match_ob.dyn_overlay_vector=f;

  dyn_overlay[category][importance].vectors.push(f);
  dyn_overlay[category][importance].ids[id]=true;
}

function dyn_overlays_reset(cat) {
  if(!dyn_overlay[cat.id])
    return;
  for(var i=0; i<importance.length; i++) {
    if(dyn_overlay[cat.id][importance[i]])
      dyn_overlay[cat.id][importance[i]].vectors=[];
  }
}

function dyn_overlays_show_all(cat, viewbox) {
  var count=0;
  if(!dyn_overlay[cat.id])
    dyn_overlay[cat.id]={};

  dyn_overlays_hide(cat);

  var cur_cache=
    list_cache.search_element(viewbox, cat.id);
  
  for(var i=0; i<importance.length; i++) {
    if(dyn_overlay[cat.id][importance[i]]) {
      count+=dyn_overlay[cat.id][importance[i]].vectors.length;
      if(cur_cache.complete_importance[importance[i]]) {
	vector_layer.addFeatures(dyn_overlay[cat.id][importance[i]].vectors);
	dyn_overlay[cat.id].current_features=
	  dyn_overlay[cat.id].current_features.concat(
	    dyn_overlay[cat.id][importance[i]].vectors);
      }
    }
  }

  if(cur_cache.complete)
    return;

  var more=Math.ceil((map.size.h*map.size.w)/(32*32));
  more-=count;
  if(more>0)
    list_load_more(cat, viewbox, more);
}

//function dyn_overlays_showall(cat) {
//  if(!dyn_overlay[cat.id])
//    return;
//
//  vector_layer.addFeatures(dyn_overlay[cat.id].vectors);
//}

function dyn_overlays_hide(cat) {
  if(!dyn_overlay[cat.id])
    return;

  vector_layer.removeFeatures(dyn_overlay[cat.id].current_features);
  dyn_overlay[cat.id].current_features=[];

}

//register_hook("show_category", dyn_overlays_show);
register_hook("hide_category", dyn_overlays_hide);
register_hook("category_load_match", dyn_overlay_show);
register_hook("category_loaded_matches", dyn_overlays_show_all);
register_hook("category_show_reset", dyn_overlays_reset);
