function doc_show(plugin) {
  alert(plugin);
}

function doc_chapter(info, ob, div, data) {
  if(data.doc) {
    var doc_div=dom_create_append(div, "div");
    doc_div.className="doc_link";

    var a=dom_create_append(doc_div, "a");
    dom_create_append_text(a, "i");
    a.href="javascript:doc_show(\""+data.doc+"\")";
  }
}

register_hook("info_show_chapter", doc_chapter);
