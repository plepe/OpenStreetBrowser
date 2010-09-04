var navigation_toolbox;

function navigation_init() {
  navigation_toolbox=new toolbox({
    icon: "img/toolbox_navigation.png",
    icon_title: "navigation"
  });
  register_toolbox(navigation_toolbox);

  var text = "<i>Navigation</i><br/><br/>At first select your home and your destination on the map.<br/><br/><img src='img/toolbox_home.png'> home<br/><img src='img/toolbox_destination.png'> destination<br/><br/>";
  navigation_toolbox.content.innerHTML=text;
}

register_hook("init", navigation_init);
