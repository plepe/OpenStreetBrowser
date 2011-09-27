function translation_to_value(value) {
  if(!value)
    value="";
  else if(typeof value=="object") {
    if(lang_genders[value[0]])
      value[0]=lang_genders[value[0]];
    value=value.join(";");
  }
  return value;
}

function translation_submit() {
}

function translation(l) {
  // submit
  this.submit=function() {
    var changed={};

    for(var i=0; i<this.form.elements.length; i++) {
      var el=this.form.elements[i];

      if(el.mode=="doc") {
	if(el.value!=el.orig_value) {
	  changed[el.file]=el.value;
	}
      }
      else
      if((el.name)&&
	 (el.value)&&
	 (el.value!=el.orig_value)&&
	 (!((el.value=="")&&(!el.orig_value)))) {
	if(!changed[el.file])
	  changed[el.file]={};

	changed[el.file][el.name]=split_semicolon(el.value);
      }
    }

    ajax("translation_save", { lang: this.lang, changed: changed, msg: "Update translation ("+this.lang+")" }, this.save_next.bind(this));
  }

  // save_next
  this.save_next=function(ret) {
    ret=ret.return_value;

    alert(lang("saved"));
    this.win.close();
  }

  this.compare=function(data) {
    var tds=this.form.getElementsByTagName("td");
    for(var i=0; i<tds.length; i++) {
      var td=tds[i];

      if(td.className=="compare") {
	var value;

	dom_clean(td);

	if((data[td.file])&&
	   (data[td.file].list)&&
	   (data[td.file].list[td.key])&&
	   (data[td.file].list[td.key].value)) {
	  value=data[td.file].list[td.key].value;
	  value=document.createTextNode(translation_to_value(value));
	}
	else
	if((data[td.file])&&
	   (data[td.file].contents)) {
	  value=document.createElement("textarea");
	  value.value=data[td.file].contents;
	  //dom_create_append_text(value, 
	}
	else
	  value=document.createTextNode("");

	td.appendChild(value);
      }
    }
  }

  // compare_recv
  this.compare_recv=function(ret) {
    this.compare(ret.return_value);
    dom_clean(this.compare_select.nextSibling);
  }

  // compare change
  this.compare_change=function() {
    var img=dom_create_append(this.compare_select.nextSibling, "img");
    img.className="loading";
    img.src="img/ajax_loader.gif";

    ajax("translation_read", { lang: this.compare_select.value }, this.compare_recv.bind(this));
  }

  // print_file_lang_str
  this.print_file_lang_str=function(file, data, tbody) {
    if(!data.order)
      return;

    for(var j=0; j<data.order.length; j++) {
      var str=data.order[j];
      var d=data.list[str];
      var tr=dom_create_append(tbody, "tr");

      // column 1
      var td=dom_create_append(tr, "td");
      td.className="id_help";

      var div_id=dom_create_append(td, "div");
      div_id.className="id";
      dom_create_append_text(div_id, str);

      if(d.help) {
	var div_help=dom_create_append(td, "div");
	div_help.className="help";
	dom_create_append_text(div_help, d.help);
      }

      // column 2
      var td=dom_create_append(tr, "td");
      td.className="value";

      var input=dom_create_append(td, "input");
      input.file=file;
      input.name=str;

      var value=translation_to_value(d.value);
      input.value=value;
      input.orig_value=value;

      // column 3
      var td=dom_create_append(tr, "td");
      td.className="compare";
      td.file=file;
      td.key=str;
      dom_create_append_text(td, "");
    }
  }


  // print_file_doc
  this.print_file_doc=function(file, data, tbody) {
    var tr=dom_create_append(tbody, "tr");

    // column 1
    var td=dom_create_append(tr, "td");
    td.className="id_help";

    if(data.help) {
      var div_help=dom_create_append(td, "div");
      div_help.className="help";
      dom_create_append_text(div_help, data.help);
    }

    var div=dom_create_append(td, "div");
    div.className="help";
    dom_create_append_text(div, "You can use WikiCreole markup on this file");

    // column 2
    var td=dom_create_append(tr, "td");
    td.className="value";

    var input=dom_create_append(td, "textarea");
    input.file=file;
    input.mode="doc";
    input.name=file;

    input.value=data.contents;
    input.orig_value=data.contents;

    // column 3
    var td=dom_create_append(tr, "td");
    td.className="compare";
    td.file=file;
    td.name="_content_";

    var input=dom_create_append(td, "textarea");
    input.disabled=true;
  }


  // show
  this.show=function(data) {
    this.form=document.createElement("form");
    this.form.action="javascript:translation_submit()";
    this.form.onsubmit=this.submit.bind(this);

    var div=dom_create_append(this.form, "div");
    div.className="content";
    var tab=dom_create_append(div, "table");

    // header
    var thead=dom_create_append(tab, "thead");
    var tr=dom_create_append(thead, "tr");
    var th=dom_create_append(tr, "th");
    th.className="id_help";
    dom_create_append_text(th, lang("translation:string_id"));

    var th=dom_create_append(tr, "th");
    th.className="value";
    dom_create_append_text(th, lang("translation:translation"));

    var th=dom_create_append(tr, "th");
    th.className="compare";
    dom_create_append_text(th, lang("translation:compare"));

    var tbody=dom_create_append(tab, "tbody");
    for(var i in data) {
      var tr=dom_create_append(tbody, "tr");
      var th=dom_create_append(tr, "th");
      th.colSpan=3;
      th.className="file";
      dom_create_append_text(th, i);

      if(!data[i])
	continue;

      var mode=i.match(/^([^:]*):/);
      switch(mode[1]) {
	case "php":
	case "category":
	  this.print_file_lang_str(i, data[i], tbody);
	  break;
	case "doc":
	  this.print_file_doc(i, data[i], tbody);
	  break;
	default:
	  /* should not come here */
      }
    }

    var div=dom_create_append(this.form, "div");
    
    var input=dom_create_append(div, "input");
    input.type="submit";
    input.value=lang("save");

    // Choose compare language
    dom_create_append_text(div, lang("translation:compare")+":");
    this.compare_select=dom_create_append(div, "select");
    this.compare_select.onchange=this.compare_change.bind(this);
    for(var i=0; i<ui_langs.length; i++) {
      var opt=dom_create_append(this.compare_select, "option");
      opt.value=ui_langs[i];
      dom_create_append_text(opt, lang("lang:"+ui_langs[i]));
      if(ui_langs[i]=="en")
	opt.selected=true;
    }
    // place holder for loading-indicator
    dom_create_append(div, "span");
    // request compare translation
    this.compare_change();

    dom_clean(this.win.content);
    this.win.content.appendChild(this.form);
  }

  // load_callback
  this.load_callback=function(ret) {
    ret=ret.return_value;

    this.show(ret);
  }

  // load
  this.load=function() {
    ajax("translation_read", { lang: this.lang }, this.load_callback.bind(this));
    this.win.content.innerHTML="<img class='loading' src='img/ajax_loader.gif'> "+t("loading");
  }

  // ask_new_next
  this.ask_new_next=function() {
    var lang=this.form.lang_code.value;

    if(!lang_code_check(lang)) {
      alert("Translation: Illegal language code");
      return;
    }

    this.lang=lang;
    dom_clean(this.win);

    this.load();
  }

  // ask_new
  this.ask_new=function() {
    this.form=dom_create_append(this.win.content, "form");
    this.form.action="javascript:translation_submit()";
    this.form.onsubmit=this.ask_new_next.bind(this);

    dom_create_append_text(this.form, lang("translation:enter_lang_code"));
    var input=dom_create_append(this.form, "input");
    input.name="lang_code";

    dom_create_append(this.form, "br");
    var input=dom_create_append(this.form, "input");
    input.type="submit";
    input.value=lang("ok");
  }

  // constructor
  this.win=new win({ title: lang("translation:name"), class: 'translation_win' });
  if(!l) {
    this.ask_new();
  }
  else {
    this.lang=l;
    this.load();
  }
}

function translation_open() {
  new translation(ui_lang);
}

function translation_new() {
  new translation();
}

function translation_files_list() {
}

function translation_options(add) {
  add.push("<a href='javascript:translation_open()'>"+lang("translation:improve")+"</a>");
  add.push("<a href='javascript:translation_new()'>"+lang("translation:new")+"</a>");
}

register_hook("options_lang", translation_options);
