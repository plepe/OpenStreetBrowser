var translation_win;
var translation_form;
var translation_compare_select;

function translation_submit() {
  var changed={};

  for(var i=0; i<translation_form.elements.length; i++) {
    var el=translation_form.elements[i];

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

  ajax("translation_save", { lang: ui_lang, changed: changed, msg: "Update translation ("+ui_lang+")" }, translation_save_next);
}

function translation_save_next(ret) {
  ret=ret.return_value;

  alert(lang("saved"));
  translation_win.close();
}

function translation_compare(data) {
  var tds=translation_form.getElementsByTagName("td");
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

function translation_compare_recv(ret) {
  translation_compare(ret.return_value);
  dom_clean(translation_compare_select.nextSibling);
}

function translation_compare_change() {
  var img=dom_create_append(translation_compare_select.nextSibling, "img");
  img.className="loading";
  img.src="img/ajax_loader.gif";

  ajax("translation_read", { lang: translation_compare_select.value }, translation_compare_recv);
}

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

function translation_print_file_lang_str(file, data, tbody) {
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

function translation_print_file_doc(file, data, tbody) {
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

function translation_show(data) {
  dom_clean(translation_win.content);

  translation_form=dom_create_append(translation_win.content, "form");
  translation_form.action="javascript:translation_submit()";

  var div=dom_create_append(translation_form, "div");
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
        translation_print_file_lang_str(i, data[i], tbody);
	break;
      case "doc":
        translation_print_file_doc(i, data[i], tbody);
	break;
      default:
        /* should not come here */
    }
  }

  var div=dom_create_append(translation_form, "div");
  
  var input=dom_create_append(div, "input");
  input.type="submit";
  input.value=lang("save");

  // Choose compare language
  dom_create_append_text(div, lang("translation:compare")+":");
  translation_compare_select=dom_create_append(div, "select");
  translation_compare_select.onchange=translation_compare_change;
  for(var i=0; i<ui_langs.length; i++) {
    var opt=dom_create_append(translation_compare_select, "option");
    opt.value=ui_langs[i];
    dom_create_append_text(opt, lang("lang:"+ui_langs[i]));
    if(ui_langs[i]=="en")
      opt.selected=true;
  }
  // place holder for loading-indicator
  dom_create_append(div, "span");
  // request compare translation
  translation_compare_change();
}

function translation_open() {
  translation_win=new win({ title: lang("translation:name"), class: 'translation_win' });
  ajax("translation_read", { lang: ui_lang }, translation_open_next1);
  translation_win.content.innerHTML="<img class='loading' src='img/ajax_loader.gif'> "+t("loading");
}

function translation_open_next1(ret) {
  ret=ret.return_value;

  translation_show(ret);
}

function translation_files_list() {
}
