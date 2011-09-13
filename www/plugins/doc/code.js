function doc_show(plugin) {
  alert(plugin);
}

function doc_chapter(info, ob, div, data) {
  if(data.doc) {
    var head=div.getElementsByTagName("h2");
    if(head.length==0)
      return;

    var doc_div=document.createElement("div");
    head[0].appendChild(doc_div);
    doc_div.className="doc_link";

    var a=dom_create_append(doc_div, "a");
    a.href="javascript:doc_show(\""+data.doc+"\")";

    var img=dom_create_append(a, "img");
    img.src="img/info.png";
  }
}

register_hook("info_show_chapter", doc_chapter);
