var translation_win;

function translation_show(data) {
  var form=dom_create_append(translation_win.content, "form");
  var tab=dom_create_append(form, "table");

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
      input.value=d.value;
      input.name=str;

      // column 3
      var td=dom_create_append(tr, "td");
      td.className="compare";
      dom_create_append_text(td, "foo");
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
