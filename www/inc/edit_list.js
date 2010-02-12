var edit_list_highest_element_id=0;
var edit_list_form;

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
}

function edit_list_set_list_data() {
  var ret="";

  ret="<?xml version='1.0' encoding='UTF-8'?>\n";

  ret+="<list>\n";
  ret+="  <name>"+edit_list_form.elements.name.value+"</name>\n";
  ret+="  <lang>"+edit_list_form.elements.lang.value+"</lang>\n";

  ret.list=[];
  for(var i=0; i<edit_list_highest_element_id; i++) {
    var data=edit_list_get_data(i);
    if(data) {
      ret+="  <element>\n";
      for(var j in data) {
	ret+="    <"+j+">"+data[j]+"</"+j+">\n";
      }
      ret+="  </element>\n";
    }
  }

  ret+="</list>\n";

  ajax_post("lists.php", { todo: 'save', id: 'new' }, ret, edit_list_callback);
}

function edit_list_element_set(id) {
  var div=document.getElementById("edit_list_element_"+id);
  var data=edit_list_get_data(id);

  div.innerHTML="<a href='javascript: edit_list_edit_element("+data.id+")'>(foo) "+data.name+"</a>"+edit_list_hidden_form(data);
}

function edit_list_get_data(id) {
  var div=document.getElementById("edit_list_element_"+id);

  var data={};
  for(var i=0; i<edit_list_form.elements.length; i++) {
    var fi=edit_list_form.elements[i];

    if(fi.name)
      if(m=fi.name.match("^"+id+"\.([a-z_]+)")) {
	data[m[1]]=fi.value;
      }
  }

  return data;
}

function edit_list_hidden_form(data) {
  ret="";

  for(var i in data) {
    ret+="<input type='hidden' name='"+data.id+"."+i+"' value='"+data[i]+"'/>\n";
  }

  return ret;
}

function edit_list_element_form(data) {
  var ret="";

  ret ="<div id='edit_element_"+data.id+"'>\n";
  if(!data.name)
    ret+="New element\n";
  else
    ret+="(foo) "+data.name+"\n";
  ret+="<input type='hidden' name='"+data.id+".id' value='"+data.id+"'/></td></tr>\n";
  ret+="<table>\n";
  ret+="<tr><td>Name:</td><td><input name='"+data.id+".name' value='"+data.name+"'/></td></tr>\n";
  ret+="<tr><td>Tag:</td><td><input name='"+data.id+".tag' value='"+data.tag+"'/></td></tr>\n";
  ret+="<tr><td colspan='2' class='help'>Please insert a tag/value-pair, e.g. \"amenity=bar\". If you want to match on several tags, e.g. christian church, write \"amenity=place_of_worship religion=christian\". If a tag can hold on of several values, write \"amenity=bar;restaurant\". See <a href='http://wiki.openstreetmap.org/wiki/Map_Features' target='_new'>Map Features</a> for possible values.</td></tr>\n";
  ret+="<tr><td>Further description:</td><td><input name='"+data.id+".description' value='"+data.description+"'/></td></tr>\n";
  ret+="<tr><td colspan='2' class='help'>Insert a tag-name which further describes this element, e.g. \"cuisine\" for restaurants. If you don't know, rather leave this field empty.</td></tr>\n";
  ret+="<tr><td>Icon:</td><td><input name='"+data.id+".icon' value='"+data.icon+"'/></td></tr>\n";

  ret+="</table>\n";
  ret+="<input type='button' value='Ok' onClick='edit_list_element_set("+data.id+")'>\n";
  ret+="</div>\n";

  return ret;
}

function edit_list_new_element() {
  var data={ id: edit_list_highest_element_id++, tag: "", description: "", name: "", icon: "" };
  var div=document.createElement("div");
  div.className='edit_list_element';
  div.innerHTML=edit_list_element_form(data);
  div.id="edit_list_element_"+data.id;

  var ellist=document.getElementById("edit_list_element_list");
  ellist.appendChild(div);
}

function edit_list_edit_element(id) {
  div=document.getElementById("edit_list_element_"+id);
  div.innerHTML=edit_list_element_form(edit_list_get_data(id));
}

function edit_list() {
  var w=new win("edit_list");
  var data={ id: 1, name: "", desc: "", lang: "english" };
  var ret="";

  ret ="<form id='edit_list' action='javascript: edit_list_set_list_data()'>\n";
  ret+="<input type='hidden' name='name' value='"+data.id+"'/>\n";
  ret+="<table>\n";
  ret+="<tr><td>Name:</td><td><input name='name' value='"+data.name+"'/></td></tr>\n";
  ret+="<tr><td>Description:</td><td><textarea name='desc'>"+data.desc+"</textarea></td></tr>\n";
  ret+="<tr><td>Language:</td><td><input name='lang' value='"+data.lang+"'/></td></tr>\n";
  ret+="</table>\n";

  ret+="<hr>\n";
  ret+="<div id='edit_list_element_list'></div>\n";
  ret+="<a href='javascript: edit_list_new_element()'>Add an element</a>\n";
  ret+="<br/>\n";

  ret+="<input type='submit' value='Save'/>\n";
  ret+="</form>\n";

  w.content.innerHTML=ret;

  edit_list_form=document.getElementById("edit_list");
}
