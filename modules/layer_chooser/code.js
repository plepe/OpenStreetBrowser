function layer_chooser_toggle_hill() {
  if(layerHill.getVisibility()==false) {
    layerHill.setVisibility(true);
  } else {
    layerHill.setVisibility(false);
  }
}

function layer_chooser_options_show(list) {
  var ret="";

  ret+="<h4>"+t("options:mapstyle")+"</h4>\n";
  ret+="<div class='options_help'>"+t("help:mapstyle")+"</div>\n";

  ret+="<table><tr><td><a href='javascript:layerOSB.map.setBaseLayer(layerOSB);close_options();'><img class='layerImg' src='modulekit_file(\"layer_chooser\", \"layerOSB.png\")'><br>OSB</a></td><td><a href='javascript:layerMapnik.map.setBaseLayer(layerMapnik);close_options();'><img class='layerImg' src='modulekit_file(\"layer_chooser\", \"layerMapnik.png\")'><br>Mapnik</a></td><td><a href='javascript:layerOsmarender.map.setBaseLayer(layerOsmarender);close_options();'><img class='layerImg' src='modulekit_file(\"layer_chooser\", \"layerOsmarender.png\")'><br>Osmarender</td><td><a href='javascript:layerCycle.map.setBaseLayer(layerCycle);close_options();'><img class='layerImg' src='modulekit_file(\"layer_chooser\", \"layerCycle.png\")'><br>Cycle Map</td><td><a href='javascript:layer_chooser_toggle_hill();close_options();'><img class='layerImg' src='modulekit_file(\"layer_chooser\", \"layerHill.png\")'><br>Schraffur</a></td></tr></table>";

  list.push([ -1, ret ]);
}

register_hook("options_show", layer_chooser_options_show);
