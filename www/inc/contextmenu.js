var contexttimer;

function rightclick(e) {
  clearTimeout(contexttimer);
  contextmenu_style=document.getElementById("contextmenu").style;
  contextmenu_style.display="block";
  contextmenu_style.top=(e.pageY-2);
  contextmenu_style.left=e.pageX;
}

function contextmenu_mouseout(e) {
  if ((e.target.tagName=="DIV") || (e.relatedTarget.tagName == "svg")) {
    contexttimer=window.setTimeout("document.getElementById('contextmenu').style.display='none'",500);
  }
}
