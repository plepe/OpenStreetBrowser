function category_rule_match(dom, cat, rule) {
  this.tags=new tags();
  this.tags.readDOM(dom);
  this.category=cat;
  this.rule=rule;
  this.id=dom.getAttribute("id");
  this.id_split=split_semicolon(dom.getAttribute("id"));

  // text
  this.list_entry=function() {
    var ret="";
    var x;
    var name="";
    var add="";

    x=this.tags.get("display_name");
    if(!x)
      x=t("unnamed");
    name=x;

    if(x=this.tags.get("display_type"))
      add=" ("+x+")";

    var li_style="";
    if(x=this.tags.get("icon")) {
      var icon=x;
      var icon_data;

      li_style+="list-style-image: url('"+x+"'); ";
//      if(icon_data=icon.match(/^\[\[Image:(.*)\]\]$/)) {
//	icon_data=icon_data[1].replace(/ /g, "_");
//	li_style+="list-style-image: url('symbols/"+icon_data+"'); ";
//      }
    }
    if(this.rule.tags.get_lang("name", ui_lang))
      var title=this.rule.tags.get_lang("name", ui_lang);
    else
      var title=this.rule.tags.get("match");

    ret="<li class='listitem' style=\""+li_style+"\" id='list_"+this.id+"' title='"+title+"'><element id='"+this.id+"' rule_id='"+this.rule.id+"'+><a href='#"+this.id+"' onMouseOver='set_highlight([\""+this.id_split.join("\", \"")+"\"])' onMouseOut='unset_highlight()'>"+name+"</a>"+add+"</element></li>\n";
    return ret;
  }
}

function category_rule(category, dom) {
  // constructor
  this.icon=null;
  if(!dom) {
    this.id=uniqid();
    this.tags=new tags(category_rule_tags_default);
  }
  else {
    this.id=dom.getAttribute("id");
    this.tags=new tags();
    this.tags.readDOM(dom);
    if(this.tags.get("icon"))
      this.icon=icon_git.get_obj(this.tags.get("icon"));
  }
  this.data=[];
  this.category=category;

  // editor_toggle
  this.editor_toggle=function() {
    if(this.content.style.display!="none")
      this.content.style.display="none";
    else
      this.content.style.display="block";
  }

  //remove
  this.remove=function() {
    this.category.remove_rule(this);
  }

  // rule_title
  this.rule_title=function(header) {
    while(header.firstChild) {
      header.removeChild(header.firstChild);
    }

    if(this.icon) {
      var img=dom_create_append(header, "img");
      img.src=this.icon.icon_url();
    }

    var txt;
    if(this.tags.get_lang("name", ui_lang))
      txt=this.tags.get_lang("name", ui_lang);
    else if(this.tags.get("match"))
      txt=this.tags.get("match");
    else
      txt="New rule";

    dom_create_append_text(header, txt);
  }

  this.choose_icon=function() {
    this.icon_chooser=new icon_chooser(this.tags.get("icon"), this.choose_icon_callback.bind(this));
  }

  this.choose_icon_callback=function(new_icon) {
    if(this.input) {
      this.input.value=new_icon;
      raise_event("change", this.input);
    }
  }

  this.edit_icon_finish=function(id) {
    if(this.input) {
      this.input.value=id;
      raise_event("change", this.input);
    }
  }

  this.edit_icon=function() {
    new icon_editor(this.icon, this.edit_icon_finish.bind(this));
  }

  this.editor_change_key=function(tags, tag) {
    var value=tag.val.value;

    if(tag.key.value=="icon") {
      var td=tag.val_td;
      while(td.firstChild)
	td.removeChild(td.firstChild);

      this.input=dom_create_append(td, "input");
      //this.input.type='hidden';
      this.input.value=value;
      tag.set_value_object(this.input);

      dom_create_append(td, "br");

      var input=dom_create_append(td, "input");
      input.type="button";
      input.value=t("choose");
      input.onclick=this.choose_icon.bind(this);

      this.preview=dom_create_append(td, "span");

      var input=dom_create_append(td, "input");
      input.type="button";
      input.value=t("edit");
      input.onclick=this.edit_icon.bind(this);

      tag.change(tag);
    }
    else if(tag.key.old_value=="icon") {
      var td=tag.val_td;
      while(td.firstChild)
	td.removeChild(td.firstChild);

      var input=dom_create_append(td, "input");
      input.value=value;
      tag.set_value_object(input);

      delete(this.preview);

      tag.change(tag);
    }
  }

  this.editor_change=function(tags, tag) {
    this.rule_title(this.header);

    if((tag)&&(tag.key.value=="icon")&&(this.preview)) {
      while(this.preview.firstChild)
	this.preview.removeChild(this.preview.firstChild);

      this.icon=icon_git.get_obj(tag.val.value);
      if(this.icon) {
	var img=dom_create_append(this.preview, "img");
	img.src=this.icon.icon_url();
      }
    }
  }

  // editor
  this.editor=function(div, visible) {
    if(!div) {
      alert("categories::editor: no valid div supplied");
      return;
    }

    this.div=div;


    this.header=document.createElement("div");
    this.div.appendChild(this.header);
    this.rule_title(this.header);
    this.header.onclick=this.editor_toggle.bind(this);

    this.content=document.createElement("div");
    if(!visible)
      this.content.style.display="none";
    this.div.appendChild(this.content);

    this.tags_editor=document.createElement("div");
    this.content.appendChild(this.tags_editor);

    var txt=document.createElement("div");
    txt.innerHTML="Tags (<a target='_new' href='http://wiki.openstreetmap.org/wiki/OpenStreetBrowser/Edit_List'>Help</a>):\n";
    this.tags_editor.appendChild(txt);

    this.tags.editor_on_change_key=this.editor_change_key.bind(this);
    this.tags.editor_on_change=this.editor_change.bind(this);
    this.tags.editor(this.tags_editor);

    var input=document.createElement("input");
    input.type="button";
    input.value="Ok";
    input.onclick=this.editor_toggle.bind(this);
    this.content.appendChild(input);

    var input=document.createElement("input");
    input.type="button";
    input.value="Remove Rule";
    input.onclick=this.remove.bind(this);
    this.content.appendChild(input);
  }

  // load_entry
  this.load_entry=function(dom) {
    var id=dom.getAttribute("id");
    if(!this.data[id]) {
      this.data[id]=new category_rule_match(dom, this.category, this);
    }
    var match=this.data[id];
    var time=new Date();
    this.data[id].access=time.getTime();

    return this.data[id];
  }

}


