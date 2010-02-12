function edit_list_set_list_data(ob) {
  var data=document.getElementById("edit_list");
//  alert(print_r(ob));
  alert("gogo");
}

function edit_list_element_set() {
  var f=document.getElementById("edit_element");
  var div=f.parentNode;

  div.data={};
  for(var i=0; i<f.elements.length; i++) {
    var fi=f.elements[i];

    if(fi.name)
      div.data[fi.name]=fi.value;
  }

  alert(print_r(div.data));
  div.innerHTML="(foo) "+div.data.foo;
}

function edit_list_new_element() {
  var data={};
  var ret="";

  ret ="<form id='edit_element' action='javascript: edit_list_element_set()'>\n";
  ret+="<table>\n";
  ret+="<tr><td colspan='2'>New element</td></tr>\n";
  ret+="<tr><td>Foo:</td><td><input name='foo' value='"+data.bar+"'/></td></tr>\n";
  ret+="</table>\n";
  ret+="<input type='submit' value='Ok'>\n";
  ret+="</form>\n";

  var div=document.createElement("div");
  div.innerHTML=ret;

  var ellist=document.getElementById("edit_list_element_list");
  ellist.appendChild(div);
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

  ret+="<input type='submit' value='Save'>\n";
  ret+="<form>\n";

  w.content.innerHTML=ret;
}
