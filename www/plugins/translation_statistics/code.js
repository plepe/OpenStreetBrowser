function translation_statistics() {
  this.win=new win({ title: lang("translation_statistics:name") });
  ajax("translation_statistics", {}, this.load_callback.bind(this));

  this.table=dom_create_append(this.win.content, "table");
  var tr=dom_create_append(this.table, "tr");

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:local"));

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:current"));

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:lang_code"));

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:base_lang"));

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:ui"));
}

translation_statistics.prototype.load_callback=function(data) {
  data=data.return_value;
  var max_ui=data.en.lang_str_count;

  for(var i in data) {
    var tr=dom_create_append(this.table, "tr");

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, data[i].name);

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, data[i]["name:"+ui_lang]);

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, i);

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, data[i].base_language);

    var rate=data[i].lang_str_count/max_ui*100.0;
    var th=dom_create_append(tr, "td");
    th.className="rate_"+Math.floor(rate/15);

    dom_create_append_text(th, sprintf("%d (%.0f%%)", data[i].lang_str_count, rate));
  }
}

function translation_statistics_win() {
  new translation_statistics();
}

function translation_statistics_options(add) {
  add.push("<a href='javascript:translation_statistics_win()'>"+lang("translation_statistics:name")+"</a>");
}

register_hook("options_lang", translation_statistics_options);
