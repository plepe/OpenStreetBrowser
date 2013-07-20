function right_bar_hide() {
  var x=document.body.className.match(/^(.*) has_right_bar(.*)$/);
  document.body.className=x[1]+x[2];
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

  if(document.getElementById("right_bar"))
    document.body.className+=" has_right_bar";
}

register_hook("init", right_bar_init);
