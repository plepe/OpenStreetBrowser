var contexttimer;

function rightclick(e) {
  clearTimeout(contexttimer);

  var e = (e) ? e : ((window.event) ? window.event : "");
  if (e.pageX || e.pageY) {
    var posx = e.pageX;
    var posy = e.pageY;
  }
  else if (e.clientX || e.clientY) {
    var posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
    var posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
  }

  contextmenu_style=document.getElementById("contextmenu").style;
  contextmenu_style.display="block";
  contextmenu_style.top=posy+"px";
  contextmenu_style.left=posx+"px";
}

function contextmenu_mouseout(e) {
  var e = (e) ? e : ((window.event) ? window.event : "");
//  var tar = (e.target) ? e.target.tagName : e.srcElement.tagName;
  var reltar = (e.relatedTarget) ? e.relatedTarget : e.toElement;

// Die Abfrage, welche von beiden Möglichkeiten gewählt werden muss, ist so noch nicht richtig leider...
//  if (Node.prototype.contains) {
//    var nu = !(document.getElementById('contextmenu').contains(reltar));
//  } else {
    var nu = !!(document.getElementById('contextmenu').compareDocumentPosition(reltar)&2);
//  }

//  document.getElementById('debugging').innerHTML+=nu+"<br/>";
  if (nu) {
    contexttimer=window.setTimeout("document.getElementById('contextmenu').style.display='none'",500);
//    window.setTimeout("document.getElementById('debugging').innerHTML='<i>Die Super-Debugging-Box 3000</i><br/>'",3000);
  }
}
