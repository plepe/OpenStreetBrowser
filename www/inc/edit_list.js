var edit_list_highest_element_id=0;
var edit_list_form;
var edit_list_win;
var list_importance=[ "international", "national", "regional", "urban", "suburban", "local" ];

function new_dom_document() {
  var text="<?xml version='1.0' encoding='UTF-8'?><list/>\n";
  if(window.DOMParser) {
    parser=new DOMParser();
    xmlDoc=parser.parseFromString(text,"text/xml");
  }
  else { // Internet Explorer
    xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async="false";
    xmlDoc.loadXML(text);
  }

  return xmlDoc;
} 

function edit_list_callback() {
  alert("saved");

  edit_list_win.close();
  edit_list_win=null;
}

function edit_list_set_list_data() {
  var ret="";
  var f=edit_list_form.getElementsByTagName("table");
  var i=0;
  var list;
  var elements=[];

  for(var fi=0; fi<f.length; fi++) {
    if(f[fi].id.match(/^editor_/)) {
      var t=tag_editors[f[fi].id];
      if(i==0) {
	list=t;
      }
      else {
	elements.push(t);
      }
      i++;
    }
  }
  
  ret="<?xml version='1.0' encoding='UTF-8'?>\n";

  ret+="<list>\n";
  ret+=list.xml("  ");

  ret.list=[];
  for(var i=0; i<elements.length; i++) {
    ret+="  <element>\n";
    ret+=elements[i].xml("    ");
    ret+="  </element>\n";
  }

  ret+="</list>\n";

  ajax_post("lists.php", { todo: 'save', id: 'new' }, ret, edit_list_callback);
}

function edit_list_element_set(id) {
  var tdiv=document.getElementById("edit_element_"+id+"_content");
  tdiv.style.display="none";
}

function edit_list_explode(id) {
  var tdiv=document.getElementById("edit_element_"+id+"_content");
  tdiv.style.display="block";
}

function edit_list_element_form(id, tags) {
  var ret="";

  ret ="<div id='edit_element_"+id+"'>\n";
  ret+="<a id='edit_element_"+id+"_name' href='javascript:edit_list_explode(\""+id+"\")'>";
  if(!tags.get("name"))
    ret+="New element";
  else
    ret+="(foo) "+tags.get("name");
  ret+="</a>\n";
  ret+="<input type='hidden' name='"+id+".id' value='yes'/>\n";

  ret+="<div id='edit_element_"+id+"_content'>\n";
  
  ret+="<p>Tags (<a target='_new' href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/Edit_List'>Help</a>):\n";

  ret+="<div>"+tags.editor()+"</div>\n";

/*  ret+="<table>\n";
  ret+="<tr><td>Name:</td><td><input name='"+data.id+".name' value='"+data.name+"'/></td></tr>\n";
  ret+="<tr><td>Tag:</td><td><input name='"+data.id+".tag' value='"+data.tag+"'/></td></tr>\n";
  ret+="<tr><td colspan='2' class='help'>Please insert a tag/value-pair, e.g. \"amenity=bar\". If you want to match on several tags, e.g. christian church, write \"amenity=place_of_worship religion=christian\". If a tag can hold on of several values, write \"amenity=bar;restaurant\". See <a href='http://wiki.openstreetmap.org/wiki/Map_Features' target='_new'>Map Features</a> for possible values.</td></tr>\n";
  ret+="<tr><td>Further description:</td><td><input name='"+data.id+".description' value='"+data.description+"'/></td></tr>\n";
  ret+="<tr><td colspan='2' class='help'>Insert a tag-name which further describes this element, e.g. \"cuisine\" for restaurants. If you don't know, rather leave this field empty.</td></tr>\n";
  ret+="<tr><td>Importance:</td><td><select name='"+data.id+".importance'>\n";
  for(var i=0; i<list_importance.length; i++) {
    ret+="  <option";
    if(data.importance==list_importance[i])
      ret+=" selected='selected'";
    ret+=">"+list_importance[i]+"</option>\n";
  }
  ret+="</select></td></tr>\n";
  ret+="<tr><td colspan='2' class='help'>What importance does this element have? E.g. a bench in a park is of local importance, not many people would want to walk miles for it. A parliament should have national importance.</td></tr>\n";
  ret+="<tr><td>Icon:</td><td><input name='"+data.id+".icon' value='"+data.icon+"'/></td></tr>\n";

  ret+="</table>\n"; */
  ret+="<input type='button' value='Ok' onClick='edit_list_element_set("+id+")'>\n";
  ret+="</div>\n";

  return ret;
}

function edit_list_new_element() {
  var _tags=new tags({ name: "", tag: "", description: "", icon: "" });
  var id=edit_list_highest_element_id++;
  var div=document.createElement("div");
  div.className='edit_list_element';
  div.innerHTML=edit_list_element_form(id, _tags);
  div.id="edit_list_element_"+id;

  var ellist=document.getElementById("edit_list_element_list");
  ellist.appendChild(div);
}

function edit_list_edit_element(id) {
  div=document.getElementById("edit_list_element_"+id);
  div.innerHTML=edit_list_element_form(edit_list_get_data(id));
}

function edit_list() {
  edit_list_win=new win("edit_list");
  var data=new tags({ id: 1, name: "", desc: "", lang: "english" });
  var ret="";

  ret ="<form id='edit_list' action='javascript: edit_list_set_list_data()'>\n";
  ret+="<input type='hidden' name='name' value='"+data.id+"'/>\n";
  ret+=data.editor();

  ret+="<hr>\n";
  ret+="<div id='edit_list_element_list'></div>\n";
  ret+="<a href='javascript: edit_list_new_element()'>Add an element</a>\n";
  ret+="<br/>\n";

  ret+="<input type='submit' value='Save'/>\n";
  ret+="</form>\n";

  edit_list_win.content.innerHTML=ret;

  edit_list_form=document.getElementById("edit_list");
}

//  ajax_direct("lists.php", { todo: "load", id: id }, lists_load_callback);
//function lists_load_callback(data) {
//  var xml=data.responseXML;
//}

function load_lists_list(id, name) {
  if(edit_list_win) {
    edit_list_win.close();
    edit_list_win=null;
  }

  category_list[id]=[];
  lang_str["cat:"+id]=[ name ];
  show_list();
}

function lists_list_callback(data) {
  var list=data.responseXML;
  var obs=list.getElementsByTagName("list");
  var ret="";

  ret+="Choose a list:<ul>\n";
  for(var i=0; i<obs.length; i++) {
    var ob=obs[i];

    ret+="<li><a href='javascript:load_lists_list(\""+ob.getAttribute("id")+
      "\", \""+ob.textContent+"\")'>"+ob.textContent+"</a></li>\n";
  }

  edit_list_win.content.innerHTML=ret;
}

function lists_list() {
  edit_list_win=new win("edit_list");
  edit_list_win.content.innerHTML="Loading ...";
  ajax_direct("lists.php", { todo: "list" }, lists_list_callback);
}
