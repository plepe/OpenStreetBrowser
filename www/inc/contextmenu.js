var contexttimer;
var contextmenu_pos;

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

  var map_div=document.getElementById("map");
  var offsetx = map_div.offsetLeft;
  var offsety = map_div.offsetTop;

  contextmenu_style=document.getElementById("contextmenu").style;
  contextmenu_style.display="block";
  contextmenu_style.top=posy+"px";
  contextmenu_style.left=posx+"px";

  contextmenu_pos=map.getLonLatFromPixel(new OpenLayers.Pixel(posx-offsetx, posy-offsety)).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
}

function contextmenu_mouseout(e) {
  var e = (e) ? e : ((window.event) ? window.event : "");
  var reltar = (e.relatedTarget) ? e.relatedTarget : e.toElement;

  if (typeof(document.getElementById('contextmenu').contains)=="object") {
    var nu = !(document.getElementById('contextmenu').contains(reltar));
  } else {
    var nu = !!(document.getElementById('contextmenu').compareDocumentPosition(reltar)&2);
  }

  if (nu) {
    contexttimer=window.setTimeout("document.getElementById('contextmenu').style.display='none'",500);
  }
}

// hide the context menu
function contextmenu_hide() {
  document.getElementById('contextmenu').style.display='none';
}

function contextmenu_entry(cell, img, text, fun) {
  // contextmenu_fire is called, when entry is selected in list
  this.contextmenu_fire=function() {
    // called saved function
    this.fun(contextmenu_pos);

    // hide the contextmenu
    contextmenu_hide();
  }

  // constructor
  this.fun=fun;
  this.cell=cell;

  // create entry in menu
  var link=document.createElement("a");
  link.href="#";
  link.onclick=this.contextmenu_fire.bind(this);
  link.innerHTML="<img src=\""+img+"\" border=\"0\" title=\"\"> "+text;
  cell.appendChild(link);
}

function contextmenu_add(img, text, fun) {
  // add a row to the table of the contextmenu
  var tab=document.getElementById("contextmenu_table");
  var row=tab.insertRow(tab.rows.length);
  var cell=document.createElement("td");
  cell.className="contextmenu_entry";
  row.appendChild(cell);

  // create the entry as a contextmenu_entry
  var entry=new contextmenu_entry(cell, img, text, fun);

  // return entry
  return entry;
}
