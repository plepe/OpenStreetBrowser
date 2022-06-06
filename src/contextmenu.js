register_hook('map-options', function (options) {
  options.contextmenu = true
  options.contextmenuItems = []

  call_hooks('contextmenu-items', options.contextmenuItems)
})
