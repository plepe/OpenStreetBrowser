function lang_chooser_save() {
  options_set("ui_lang", options_select_get("ui_lang"));
  options_set("data_lang", options_select_get("data_lang"));
  if(data_lang=="auto")
    data_lang=ui_lang;
}

function lang_chooser_entry(list) {
  var ret1;
  var parts=document.createElement("div");
  parts.className="lang_chooser";

  ret1 ="<h4>"+t("options:language_support")+"</h4>\n";
  ret1+="<div class='options_help'>"+t("help:language_support")+"</div>\n";
  ret1+="<p>\n";

  var ui_langs_x={};
  for(var i=0; i<ui_langs.length; i++) {
    var str=language_list[ui_langs[i]];
    if(lang("lang:"+ui_langs[i])!=str)
      str+=" ("+lang("lang:"+ui_langs[i])+")";
    ui_langs_x[ui_langs[i]]=str;
  }

  // if ui_lang is set to a not defined UI lang add this option
  if(!in_array(ui_langs, ui_lang)) {
    var str="";
    if(language_list[ui_lang])
      str=language_list[ui_lang];
    else
      str=ui_lang;

    if(lang("lang:"+ui_lang)!=str)
      str+=" ("+lang("lang:"+ui_lang)+")";

    ui_langs_x[ui_lang]=str;
  }

  ret1+=options_select("ui_lang", ui_langs_x);
  ret1+="<br/>\n";

  var ui_langs_x={};
  l=[];
  l[""]="";
  l["auto"]=t("lang:auto");
  for(var i in language_list) {
    l[i]=language_list[i];
    if(lang("lang:"+i)!=l[i])
      l[i]+=" ("+lang("lang:"+i)+")";
  }
  l[""]=t("lang:");

  ret1+=options_select("data_lang", l);
  ret1+="</p>\n";

  parts.innerHTML=ret1;

  var add=[];
  call_hooks("options_lang", add);
  ret1=add.join(" |\n");
  var d=dom_create_append(parts, "div");
  d.innerHTML=ret1;

  list.push([ -1, parts ]);
}

// lang_chooser_win::constructor
function lang_chooser_win() {
  this.win=new win({ class: 'options_win', title: lang('lang_chooser:name') });

  var ret=document.createElement("form");
  ret.onsubmit=this.save.bind(this); //"javascript:save_options()";
  ret.id="options_form";

  var list=[];
  lang_chooser_entry(list);

  var parts=dom_create_append(ret, "div");
  parts.className="options_parts";
  parts.appendChild(list[0][1]);

  var d=dom_create_append(ret, "div");
  d.className="options_interact";

  var i=dom_create_append(d, "input");
  i.type="submit";
  i.value=lang("save");

  var i=dom_create_append(d, "input");
  i.type="button";
  i.onclick=this.close.bind(this);
  i.value=lang("cancel");

  this.win.content.appendChild(ret);
}

// lang_chooser_win::close
lang_chooser_win.prototype.close=function() {
  this.win.close();
  delete(this.win);
}

// lang_chooser_win::save
lang_chooser_win.prototype.save=function() {
  lang_chooser_save();
  this.close();
  return false;
}

function lang_chooser_show() {
  new lang_chooser_win();
}

register_hook("options_show", lang_chooser_entry);
register_hook("options_save", lang_chooser_save);
