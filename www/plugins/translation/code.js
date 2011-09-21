var translation_win;

function translation_show(data) {
  var form=dom_create_append(translation_win.content, "form");
  var tab=dom_create_append(form, "table");

  var tr=dom_create_append(tab, "tr");
  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation:string_id"));
  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation:translation"));
  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation:compare"));

  for(var i in data) {
    var tr=dom_create_append(tab, "tr");
    var th=dom_create_append(tr, "th");
    th.colSpan=3;
    dom_create_append_text(th, i);

    if(data[i].order)
    for(var j=0; j<data[i].order.length; j++) {
      var str=data[i].order[j];
      var d=data[i].list[str];
      var tr=dom_create_append(tab, "tr");

      var td=dom_create_append(tr, "td");
      dom_create_append_text(td, str);

      var td=dom_create_append(tr, "td");
      var input=dom_create_append(td, "input");
      input.value=d.value;
      input.name=str;

//      var td=dom_create_append(tr, "td");
//      dom_create_append_text(td, str);
    }
  }
}

function translation_open() {
  translation_win=new win({ title: lang("translation:name"), class: 'translation_win' });
  ajax("translation_read", { lang: "en" }, translation_open_next1);
}

function translation_open_next1(ret) {
  ret=ret.return_value;

  translation_show(ret);
}

function translation_files_list() {
}
