var translation_win;
var translation_form;

function translation_submit() {
  var changed={};

  for(var i=0; i<translation_form.elements.length; i++) {
    var el=translation_form.elements[i];

    if((el.name)&&
       (el.value!=el.orig_value)&&
       (!((el.value=="")&&(!el.orig_value)))) {
      if(!changed[el.file])
	changed[el.file]={};

      changed[el.file][el.name]=split_semicolon(el.value);
    }
  }

  ajax("translation_save", { lang: ui_lang, changed: changed }, translation_save_next);
}

function translation_save_next(ret) {
  ret=ret.return_value;
}

function translation_compare(data) {
  var tds=translation_form.getElementsByTagName("td");
  for(var i=0; i<tds.length; i++) {
    var td=tds[i];

    if(td.className=="compare") {
      dom_clean(td);
      var value=data[td.file].list[td.key].value;
      value=translation_to_value(value);
      dom_create_append_text(td, value);
    }
  }
}

function translation_compare_recv(ret) {
  translation_compare(ret.return_value);
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

    if(data[i].order)
    for(var j=0; j<data[i].order.length; j++) {
      var str=data[i].order[j];
      var d=data[i].list[str];
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
      input.file=i;
      input.name=str;

      var value=translation_to_value(d.value);
      input.value=value;
      input.orig_value=value;

      // column 3
      var td=dom_create_append(tr, "td");
      td.className="compare";
      td.file=i;
      td.key=str;
      dom_create_append_text(td, "");
    }
  }

  var div=dom_create_append(translation_form, "div");
  
  var input=dom_create_append(div, "input");
  input.type="submit";
  input.value=lang("save");

  ajax("translation_read", { lang: "en" }, translation_compare_recv);
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
