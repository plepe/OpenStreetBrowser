var navigation_toolbox;

function navigation_set_home(pos) {
}

function navigation_set_destination(pos) {
}

function navigation_init() {
  navigation_toolbox=new toolbox({
    icon: "plugins/navigation/icon.png",
    icon_title: "navigation",
    weight: -3,
  });
  register_toolbox(navigation_toolbox);

  if(plugins_loaded("contextmenu")) {
    contextmenu_add("plugins/navigation/home.png", "set home", navigation_set_home);
    contextmenu_add("plugins/navigation/destination.png", "set destination", navigation_set_destination);
  }

  var text = "<i>Navigation</i><br/><br/>At first select your home and your destination on the map.<br/><br/><img src='plugins/navigation/home.png'> home<br/><img src='plugins/navigation/destination.png'> destination<br/><br/>";
  navigation_toolbox.content.innerHTML=text;
}

register_hook("init", navigation_init);
