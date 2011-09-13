function doc_show_callback(win, ret) {
  if(!ret.return_value) {
    alert("doc: no value returned!");
    return;
  }

  ret=ret.return_value;

  dom_clean(win.div_content);

  var content=creole(ret);
  win.div_content.appendChild(content);
}

function doc_close(w) {
  w.close();
}

function doc_show(plugin) {
  var w=new win("doc_win");
  w.div_content=dom_create_append(w.content, "div");
  w.div_content.className="content";

  // content
  w.div_content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

  ajax("doc_get", { plugin: plugin }, doc_show_callback.bind(this, w));

  // close button
  w.div_interact=dom_create_append(w.content, "div");
  w.div_interact.className="interact";

  var input=dom_create_append(w.div_interact, "input");
  input.type="button";
  input.value="close";
  input.onclick=doc_close.bind(this, w);
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
