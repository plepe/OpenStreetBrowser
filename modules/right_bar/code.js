function right_bar_hide() {
  var links=document.getElementsByTagName("link");
  for(var i=0; i<links.length; i++) {
    if(links[i].href.match(modulekit_file("right_bar", "style.css"))) {
      links[i].parentNode.removeChild(links[i]);
    }
  }

  var div=document.getElementById("right_bar");
  div.parentNode.removeChild(div);
}

function right_bar_init() {
  var iframe=document.getElementById("twitterwall");
  if(!iframe)
    return;
  
  iframe=iframe.getElementsByTagName("iframe");
  if(!iframe.length)
    return;
  
  iframe=iframe[0];

  iframe.style.minWidth="150px";
  iframe.width=150
}

register_hook("init", right_bar_init);
