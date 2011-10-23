function category_chooser(callback) {
  // cancel
  this.cancel=function() {
    this.win.close();
  }

  // choose
  this.choose=function(id) {
    callback("osm:"+id);
    this.win.close();
  }

  // create category
  this.create_category=function() {
    this.win.close();
    category_window_create_category();
  }

  // load_callback
  this.load_callback=function(data) {
    var list=data.responseXML;
    var obs=list.getElementsByTagName("category");
    var ret="";

    dom_clean(this.win.content);
    dom_create_append_text(this.win.content, lang("category_chooser:choose")+":");
    var ul=dom_create_append(this.win.content, "ul");

    for(var i=0; i<obs.length; i++) {
      var ob=obs[i];

      var li=dom_create_append(ul, "li");

      var a=dom_create_append(li, "a");
      a.href="#";
      a.onclick=this.choose.bind(this, ob.getAttribute("id"));

      var text=lang("unnamed");
      if(ob.firstChild)
	text=ob.firstChild.nodeValue;
      text=lang_parse(text);
	
      dom_create_append_text(a, text);

      dom_create_append_text(li, " ");

      var text=sprintf("%s", ob.getAttribute("id"));
      var span=dom_create_append(li, "span");
      span.className="category_id";
      dom_create_append_text(span, text);
    }

    var input=dom_create_append(this.win.content, "input");
    input.type="button";
    input.value=lang("category_chooser:new");
    input.onclick=this.create_category.bind(this);

    dom_create_append(this.win.content, "br");

    var input=dom_create_append(this.win.content, "input");
    input.type="button";
    input.value=lang("cancel");
    input.onclick=this.cancel.bind(this);
  }

  // constructor
  this.win=new win({ class: "category_chooser", title: lang('category', 2)});
  this.win.content.appendChild(ajax_indicator_dom());

  ajax_direct("categories.php", { todo: "list", lang: ui_lang }, this.load_callback.bind(this));
}
