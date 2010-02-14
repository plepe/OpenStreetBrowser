var tag_editors={};

function tags(d) {
  var data;

  if(typeof d=="object") {
    data=d;
  }

  this.get=function(k) {
    return data[k];
  }

  this.get_lang=function(k) {
    var ret;
    
    if(ret=data[k+":"+lang])
      return ret;

    return data[k];
  }

  this.get_available_languages=function(k) {
    var ret=[];

    for(var i in data) {
      if((m=i.match(/^(.*):(.*)$/, k))&&(m[1]==k))
	ret.push(m[2]);
    }

    return ret;
  }

  this.set=function(k, v) {
    data[k]=v;
  }

  this.erase=function(k) {
    delete(data[k]);
  }

  this.data=function() {
    return data;
  }

  this.editor=function(div) {
    var ret="";
    var i=0;
    var editor_id=uniqid("editor_");
    tag_editors[editor_id]=this;

    ret+="<table id='"+editor_id+"'>\n";
    for(var key in data) {
      ret+="  <tr>\n";
      ret+="    <td><input name='key' value=\""+key+"\" onChange='editor_update(\""+editor_id+"\")'></td>\n";
      ret+="    <td><input name='val' value=\""+data[key]+"\" onChange='editor_update(\""+editor_id+"\")'></td>\n";
      ret+="  </tr>\n";
    }
    ret+="</table>";
    ret+="<a href='javascript:editor_add_tag(\""+editor_id+"\")'>Add Tag</a>\n";

    if(div)
      div.innerHTML=ret;

    return ret;
  }

  this.editor_add_tag=function(editor_id) {
    var table=document.getElementById(editor_id);
    var tr=table.insertRow(table.rows.length);
    var ret="";

    ret+="    <td><input name='key' onChange='editor_update(\""+editor_id+"\")'></td>\n";
    ret+="    <td><input name='val' onChange='editor_update(\""+editor_id+"\")'></td>\n";

    tr.innerHTML=ret;
  }

  this.editor_update=function(editor_id) {
    var table=document.getElementById(editor_id);
    var d={};
    
    for(var i=0; i<table.rows.length; i++) {
      var tr=table.rows[i];
      var k=tr.cells[0].firstChild.value;
      var v=tr.cells[1].firstChild.value;
      d[k]=v;
    }

    data=d;
  }
}

function editor_add_tag(editor_id) {
  return tag_editors[editor_id].editor_add_tag(editor_id);
}

function editor_update(editor_id) {
  return tag_editors[editor_id].editor_update(editor_id);
}
