function right_bar_hide() {
  var links=document.getElementsByTagName("link");
  for(var i=0; i<links.length; i++) {
    if(links[i].href.match("plugins/right_bar/style.css\?")) {
      links[i].parentNode.removeChild(links[i]);
    }
  }

  var div=document.getElementById("right_bar");
  div.parentNode.removeChild(div);
}
