var contextmenu_timer;
var contextmenu_pos;
var contextmenu_items=[];

function contextmenu_rightclick(e) {
  clearTimeout(contextmenu_timer);

  var e = (e) ? e : ((window.event) ? window.event : "");
  if (e.pageX || e.pageY) {
    var posx = e.pageX;
    var posy = e.pageY;
  }
  else if (e.clientX || e.clientY) {
    var posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
    var posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
  }
  else {
    // calculate positions from map click
    alert(map.getEventPixel(e));
    var posx = e.xy.x;
    var posy = e.xy.y;

    var ob=e.target;
    while(ob) {
      if(typeof ob.offsetLeft=="number") {
	posx+=ob.offsetLeft;
	posy+=ob.offsetTop;
      }

      ob=ob.parentNode;
    }
  }

  var pos=map.getEventCoordinate(e);
  contextmenu_pos=ol.proj.transform(pos, 'EPSG:3857', 'EPSG:4326');

  var contextmenu=document.getElementById("contextmenu");
  contextmenu_compile(contextmenu_pos);

  var contextWidth = contextmenu.offsetWidth;
  var contextHeight = contextmenu.offsetHeight;
  var contextmenu_pointer = document.getElementById("contextmenu_pointer");
  if(posx >= (window.innerWidth-contextWidth)) {
    posx-=contextWidth;
    contextmenu_pointer.style.borderLeft="none";
    contextmenu_pointer.style.borderRight="2px solid black";
    contextmenu_pointer.style.left="";
    contextmenu_pointer.style.right="-2px";
  } else {
    contextmenu_pointer.style.borderLeft="2px solid black";
    contextmenu_pointer.style.borderRight="none";
    contextmenu_pointer.style.left="-2px";
    contextmenu_pointer.style.right="";

  }
  if(posy >= (window.innerHeight-contextHeight)) {
    posy-=contextHeight;
    contextmenu_pointer.style.borderTop="none";
    contextmenu_pointer.style.borderBottom="2px solid black";
    contextmenu_pointer.style.top="";
    contextmenu_pointer.style.bottom="-2px";
  } else {
    contextmenu_pointer.style.borderTop="2px solid black";
    contextmenu_pointer.style.borderBottom="none";
    contextmenu_pointer.style.top="-2px";
    contextmenu_pointer.style.bottom="";
  }

  contextmenu.style.top=posy+"px";
  contextmenu.style.left=posx+"px";
  contextmenu.style.display="block";

  call_hooks("contextmenu_shown", contextmenu_pos);
}

function contextmenu_mouseout(e) {
  var e = (e) ? e : ((window.event) ? window.event : "");
  var reltar = (e.relatedTarget) ? e.relatedTarget : e.toElement;
  var contextmenu=document.getElementById("contextmenu");

  if (typeof(contextmenu.contains)=="object") {
    var nu = !(contextmenu.contains(reltar));
  } else {
    var nu = !!(contextmenu.compareDocumentPosition(reltar)&2);
  }

  if (nu) {
    contextmenu_timer=window.setTimeout("contextmenu_hide()",500);
  }
}

// hide the context menu
function contextmenu_hide() {
  document.getElementById('contextmenu').style.display='none';
}

function contextmenu_entry(options, fun) {
  // contextmenu_fire is called, when entry is selected in list
  this.contextmenu_fire=function() {
    // called saved function
    this.fun(contextmenu_pos);

    // hide the contextmenu
    contextmenu_hide();
  }

  // constructor
  this.fun=fun;
  this.div=document.createElement("div");
  this.options=options;

  // create entry in menu
  this.div.onclick=this.contextmenu_fire.bind(this);

  var text = "";
  if(this.options.img)
    text += "<img src=\""+this.options.img+"\" border=\"0\" title=\"\"> ";
  text += this.options.text;

  this.div.innerHTML = text;
}

function contextmenu_add(img, text, fun, options) {
  if(!options)
    options={};
  options.img=img;
  options.text=text;

  // create the entry as a contextmenu_entry
  var entry=new contextmenu_entry(options, fun);

  // items
  contextmenu_items.push(entry);

  // return entry
  return entry;
}

function contextmenu_compile(contextmenu_pos) {
  // add a row to the table of the contextmenu
  var tab=document.getElementById("contextmenu_table");
  dom_clean(tab);

  var contextmenu_add_items = [];
  call_hooks("contextmenu_add_items", contextmenu_add_items, contextmenu_pos);

  var items = contextmenu_items.concat(contextmenu_add_items);

  // prepare weightsort
  var list=[];
  for(var i=0; i<items.length; i++) {
    var item=items[i];

    list.push([ item.options.weight, item ]);
  }
  list=weight_sort(list);

  for(var i=0; i<list.length; i++) {
    var row=tab.insertRow(tab.rows.length);
    var cell=document.createElement("td");
    cell.className="contextmenu_entry";
    row.appendChild(cell);
    cell.appendChild(list[i].div);
  }
}

function contextmenu_init() {
  var btn=options_get("contextmenu_mouse_button");
  if(!btn)
    btn="left";

  switch(btn) {
    case "right":
      map.div.oncontextmenu = function noContextMenu(e) {
	contextmenu_rightclick(e);
	return false; //cancel the right click of brower
      };
      break;
    case "left":
      register_hook("view_click", contextmenu_rightclick);
      break;
  }
}

function contextmenu_options_show(list) {
  var ret="";
  
  ret+="<h4>"+lang("contextmenu:head")+"</h4>\n";
  ret+="<div class='options_help'>"+lang("contextmenu:help")+"</div>\n";
  ret+=options_radio("contextmenu_mouse_button", [ "left", "right" ]);

  list.push([ 2, ret ]);
}

function contextmenu_options_save() {
  var r=options_radio_get("contextmenu_mouse_button");
  options_set("contextmenu_mouse_button", r);
}

register_hook("init", contextmenu_init);
register_hook("options_show", contextmenu_options_show);
register_hook("options_save", contextmenu_options_save);
