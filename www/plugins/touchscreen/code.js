var touchscreen_enabled;

function touchscreen_init() {
  touchscreen_enabled='ontouchstart' in document.documentElement;
}

register_hook("init", touchscreen_init);
