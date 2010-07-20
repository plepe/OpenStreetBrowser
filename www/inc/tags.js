var tag_editors={};

function tags(d) {
  var data;

  if(typeof d=="object") {
    data=d;
  }
  else {
    data={};
  }

  this.get=function(k) {
    return data[k];
  }

  this.get_multi=function(k) {
    return split_semicolon(data[k]);
  }

  this.get_lang=function(k, l) {
    var ret;
    if(!l)
      l=data_lang;
    
    if(ret=data[k+":"+l])
      return ret;

    return data[k];
  }

  this.get_lang_multi=function(k, l) {
    var ret;
    if(!l)
      l=data_lang;
    
    if(ret=data[k+":"+l])
      return split_semicolon(ret);

    return split_semicolon(data[k]);
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

  this.xml=function(indent) {
    var ret="";
    if(!indent)
      indent="";

    for(var key in data) {
      ret+=indent+"<tag k=\""+key+"\" v=\""+data[key]+"\" />\n";
    }

    return ret;
  }

  this.parse=function(str, l) {
    str=split_semicolon(str);
    if(!l)
      l=data_lang;

    for(var i=0; i<str.length; i++) {
      var match_all=true;
      var ret="";
      var def=str[i];

      while(def!="") {
	var m;
	if(m=def.match(/^\[([A-Za-z0-9_:]+)\]/)) {
	  var value;
	  if(!(value=this.get(m[1]+":"+l)))
	    if(!(value=this.get(m[1])))
	      match_all=false;

	  def=def.substr(m[0].length);
	  ret+=value;
	}
	else {
	  ret+=def.substr(0, 1);
	  def=def.substr(1);
	}
      }

      if(match_all)
	return ret;
    }

    return null;
  }

  function editor_tag(tags_editor, tr, key, value) {
    this.tags_editor=tags_editor;

    this.remove=function() {
      this.tags_editor.table.removeChild(this.tr); //deleteRow(row);
      this.tags_editor.editor_update();
    }

    this.tr=tr;

    var td=document.createElement("td");
    this.tr.appendChild(td);

    this.key_input=document.createElement("input");
    this.key_input.name="key";
    this.key_input.value=key;
    td.appendChild(this.key_input);

    var td=document.createElement("td");
    this.tr.appendChild(td);

    this.val_input=document.createElement("input");
    this.val_input.name="value";
    this.val_input.value=value;
    td.appendChild(this.val_input);

    var td=document.createElement("td");
    this.tr.appendChild(td);

    var input=document.createElement("input");
    input.type="button";
    input.onclick=this.remove.bind(this);
    input.value="X";
    td.appendChild(input);
  }

  this.editor=function(div) {
    if(!div) {
      alert("tags::editor: no valid div supplied");
      return;
    }

    var ret="";
    this.editor_id=uniqid("editor_");
    tag_editors[this.editor_id]=this;

    this.table=document.createElement("table");
    this.table.id=this.editor_id;

    for(var key in data) {
      var tr=document.createElement("tr");
      this.table.appendChild(tr);

      new editor_tag(this, tr, key, data[key]);
    }

    div.appendChild(this.table);

    var input=document.createElement("input");
    input.type="button";
    input.onclick=this.editor_add_tag.bind(this);
    input.value="Add Tag";
    div.appendChild(input);
  }

  this.editor_add_tag=function() {
    var l=this.table.rows.length;
    var tr=this.table.insertRow(l);

    new editor_tag(this, tr, '', '');
  }

  this.editor_update=function() {
    var table=document.getElementById(this.editor_id);
    var d={};
    
    if(!table)
      return null;
    
    for(var i=0; i<table.rows.length; i++) {
      var tr=table.rows[i];
      var k=tr.cells[0].firstChild.value;
      var v=tr.cells[1].firstChild.value;
      d[k]=v;
    }

    data=d;
  }

  this.readDOM=function(dom) {
    var cur=dom.firstChild;

    while(cur) {
      if(cur.nodeName=="tag") {
	this.set(cur.getAttribute("k"), cur.getAttribute("v"));
      }
      cur=cur.nextSibling;
    }
  }
}

function editor_add_tag(editor_id) {
  return tag_editors[editor_id].editor_add_tag();
}

function editor_update() {
  return tag_editors[editor_id].editor_update();
}

function editor_remove_row(editor_id, row) {
  return tag_editors[editor_id].editor_remove_row(row);
}
