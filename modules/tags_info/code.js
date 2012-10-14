function tags_info_translated(chapters, ob) {
  var ul=document.createElement("ul");
  var disp=deep_clone(ob.tags.data());

  // See plugin Tags Hide how to handle this hook
  call_hooks("info_tags_show", disp, ob);

  for(var key in disp) {
    var li=dom_create_append(ul, "li");

    if(is_dom(disp[key])) {
      dom_create_append_text(li, lang("tag:"+key)+": ");
      li.appendChild(disp[key]);
    }
    else {
      dom_create_append_text(li, lang("tag:"+key)+": "+
        (lang("tag:"+key+"="+disp[key])));
    }
  }

  return ul;
}

function tags_info_info(chapters, ob) {
  if(!ob.tags)
    return;

  // open tab manager
  var tabs=new tab_manager();

  // translated tags
  var t=new tab({ title: lang("tags_info:translated") });
  var c=tags_info_translated(chapters, ob);
  t.content.appendChild(c);
  tabs.register_tab(t);

  // raw tags
  var t=new tab({ title: lang("tags_info:raw") });
  var c=ob.tags.info();
  t.content.appendChild(c);
  tabs.register_tab(t);

  chapters.push({ head: 'tags', content: tabs.div });
}

register_hook("info", tags_info_info);
