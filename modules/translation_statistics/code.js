function translation_statistics() {
  this.win=new win({ title: lang("translation_statistics:name"), class: 'translation_statistics' });
  var req=ajax("translation_statistics", {}, this.load_callback.bind(this));

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

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:tags"));

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:category"));

  var th=dom_create_append(tr, "th");
  dom_create_append_text(th, lang("translation_statistics:total"));

  this.indicator=ajax_indicator_dom(req)
  this.win.content.appendChild(this.indicator);
}

translation_statistics.prototype.load_callback=function(data) {
  data=data.return_value;
  var max_ui=data.total.lang_str_count;
  var max_tags=data.total.tags_count;
  var max_category=data.total.category_count;
  var max_total=max_ui+max_tags+max_category;

  for(var i in data) {
    if(i=="total")
      continue;

    var sum=0;

    var tr=dom_create_append(this.table, "tr");
    if(i==ui_lang)
      tr.className="current";

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, data[i].name);

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, data[i]["name:"+ui_lang]);

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, i);

    var th=dom_create_append(tr, "td");
    dom_create_append_text(th, data[i].base_language);

    var rate=data[i].lang_str_count/max_ui*100.0;
    sum+=data[i].lang_str_count;
    var th=dom_create_append(tr, "td");
    th.className="rate rate_"+Math.floor(rate/15);

    dom_create_append_text(th, sprintf("%d (%.0f%%)", data[i].lang_str_count, rate));

    var rate=data[i].tags_count/max_tags*100.0;
    sum+=data[i].tags_count;
    var th=dom_create_append(tr, "td");
    th.className="rate rate_"+Math.floor(rate/15);

    dom_create_append_text(th, sprintf("%d (%.0f%%)", data[i].tags_count, rate));

    var rate=data[i].category_count/max_category*100.0;
    sum+=data[i].category_count;
    var th=dom_create_append(tr, "td");
    th.className="rate rate_"+Math.floor(rate/15);

    dom_create_append_text(th, sprintf("%d (%.0f%%)", data[i].category_count, rate));

    var rate=sum/max_total*100.0;
    var th=dom_create_append(tr, "td");
    th.className="rate rate_"+Math.floor(rate/15);

    dom_create_append_text(th, sprintf("%d (%.0f%%)", sum, rate));
  }

  this.win.content.removeChild(this.indicator);
}

function translation_statistics_win() {
  new translation_statistics();
}

function translation_statistics_options(add) {
  add.push("<a href='javascript:translation_statistics_win()'>"+lang("translation_statistics:name")+"</a>");
}

register_hook("options_lang", translation_statistics_options);
