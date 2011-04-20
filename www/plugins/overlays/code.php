<?
$overlays_layers=array();

/**
 * register a TMS overlay for the OpenLayers map
 * @param string an id for this overlays
 * @url string the base url for the tiles
 * @options layer options as defined by OpenLayers
 */
function overlays_register($id, $url, $options) {
  global $overlays_layers;

  $overlays_layers[$id]=array($url, $options);
}

function overlays_html_done() {
  global $overlays_layers;

  html_export_var(array("overlays_add"=>$overlays_layers));
}

function overlays_init() {
  global $overlays_add;

  if(isset($overlays_add))
    foreach($overlays_add as $id=>$layer_conf) {
      overlays_register($id, $layer_conf[0], $layer_conf[1]);
    }
}

register_hook("html_done", "overlays_html_done");
register_hook("init", "overlays_init");
