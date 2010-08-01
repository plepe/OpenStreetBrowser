function category_chooser(callback) {
  // cancel
  this.cancel=function() {
    this.win.close();
  }

  // choose
  this.choose=function(id) {
    callback(id);
    this.win.close();
  }

  // load_callback
  this.load_callback=function(data) {
    var list=data.responseXML;
    var obs=list.getElementsByTagName("category");
    var ret="";

    dom_clean(this.win.content);
    dom_create_append_text(this.win.content, "Choose a category:");
    var ul=dom_create_append(this.win.content, "ul");

    for(var i=0; i<obs.length; i++) {
      var ob=obs[i];

      var li=dom_create_append(ul, "li");
      var a=dom_create_append(li, "a");
      a.onclick=this.choose.bind(this, ob.getAttribute("id"));

      dom_create_append_text(a, ob.firstChild.nodeValue);
    }

    //ret+="<p><a href='javascript:edit_list()'>New category</a><br>\n";
    var input=dom_create_append(this.win.content, "input");
    input.type="button";
    input.value=t("cancel");
    input.onclick=this.cancel.bind(this);
  }

  // constructor
  this.win=new win("category_chooser");
  this.win.content.innerHTML="Loading ...";

  ajax_direct("categories.php", { todo: "list" }, this.load_callback.bind(this));
}
