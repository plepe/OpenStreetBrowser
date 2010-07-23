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

    this.change_key=function(ev) {
      this.tags_editor.editor_change_key(this);
    }

    this.change=function(ev) {
      this.tags_editor.editor_change(this);
    }

    this.set_value_object=function(ob) {
      this.val=ob;
      this.val.name="value";
      this.val.onchange=this.change.bind(this);
    }

    this.remove=function() {
      this.tags_editor.table.removeChild(this.tr); //deleteRow(row);
      this.tags_editor.editor_update();
    }

    this.tr=tr;
    this.tr.tag=this;

    var td=document.createElement("td");
    this.tr.appendChild(td);

    this.key=document.createElement("input");
    this.key.name="key";
    this.key.value=key;
    this.key.onchange=this.change_key.bind(this);
    td.appendChild(this.key);

    this.val_td=document.createElement("td");
    this.tr.appendChild(this.val_td);

    this.val=document.createElement("input");
    this.val.name="value";
    this.val.value=value;
    this.val.onchange=this.change.bind(this);
    this.val_td.appendChild(this.val);

    var td=document.createElement("td");
    this.tr.appendChild(td);

    var input=document.createElement("input");
    input.type="button";
    input.onclick=this.remove.bind(this);
    input.value="X";
    td.appendChild(input);
  }

  this.editor_change_key=function(tag) {
    if(this.editor_on_change_key)
      this.editor_on_change_key(this, tag);

    this.editor_change(tag);
  }

  this.editor_change=function(tag) {
    this.editor_update();
    if(this.editor_on_change)
      this.editor_on_change(this, tag);
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
      var tag=table.rows[i].tag;
      if(tag) {
	var k=tag.key.value;
	var v=tag.val.value;
	d[k]=v;
      }
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
