<?php include "conf.php"; /* load a local configuration */ ?>
<?php session_start(); ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php call_hooks("init"); /* initialize submodules */ ?>
<?php
if (isset($config['categoriesAlwaysReload']) && $config['categoriesAlwaysReload']) {
  $config['categoriesRev'] = uniqid();
}
elseif (isset($config['categoriesDir'])) {
  if (file_exists("{$config['categoriesDir']}/.git")) {
    exec("chdir " . escapeShellArg($config['categoriesDir']) . "; git rev-parse --short HEAD", $x);
    $config['categoriesRev'] = $x[0];
  }
  else {
    $config['categoriesRev'] = $modulekit['version'];
  }
}

html_export_var(array(
  'config' => $config,
));
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>OpenStreetBrowser</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="node_modules/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="node_modules/leaflet-geosearch/assets/css/leaflet.css" />
  <link rel="stylesheet" href="node_modules/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
  <script src="node_modules/leaflet/dist/leaflet.js"></script>
  <script src="node_modules/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
  <?php print modulekit_to_javascript(); /* pass modulekit configuration to JavaScript */ ?>
  <?php print modulekit_include_js(); /* prints all js-includes */ ?>
  <?php print modulekit_include_css(); /* prints all css-includes */ ?>
  <?php print_add_html_headers(); /* print additional html headers */ ?>
  <script src="dist/openstreetbrowser.js?<?=$modulekit['version']?>"></script>
</head>
<body>
  <div id='map'></div>
  <div id='mapShadow'></div>
  <div id='sidebar'>
    <div id='header'>
      <img src='img/osb_logo.png'>
      <div id='title'>OpenStreet <span class='large'>Browser</span><div class='version' title='<?=$modulekit['version']?>'><?php print substr($modulekit['version'], 0, strpos($modulekit['version'], '+')); ?></div></div>
    </div>
    <div id='content'>
    </div>
    <div id='footer'>
      <ul id='menu'>
        <li><a target='_blank' href='https://github.com/plepe/openstreetbrowser'>Code</a></li>
      </ul>
    </div>
  </div>
  <div id='loadingIndicator'>
  </div>
</body>
</html>
