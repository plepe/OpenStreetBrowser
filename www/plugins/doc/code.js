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
  var w=new win({ "class": "doc_win", "title": lang("head:doc") });

  if(plugins_list.talk) {
    // add tabs to window
    var tabs=new tab_manager(w.content);
    var doc_tab=new tab({ title: lang("head:doc") });
    tabs.register_tab(doc_tab);
    w.div_content=dom_create_append(doc_tab.content, "div");

    // add tab talk
    var _talk_tab=talk_tab(plugin);
    tabs.register_tab(_talk_tab);
  }
  else {
    w.div_content=dom_create_append(w.content, "div");
  }

  w.div_content.className="content";

  // content
  w.div_content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading");

  ajax("doc_get", { path: plugin }, doc_show_callback.bind(this, w));
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
