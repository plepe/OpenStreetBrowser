function tab(options) {
  // activate
  this.activate=function() {
    this.manager.activate(this);
  }

  // deactivate
  this.deactivate=function() {
    this.manager.deactivate(this);
  }

  // close
  this.close=function() {
    if(this.onclose)
      this.onclose();

    this.manager.unregister_tab(this);
  }

  // constructor
  this.content=document.createElement("div");
  this.content.className="tab_content";
  this.header=document.createElement("span");
  this.header.className="tab_header";
  this.header.onclick=this.activate.bind(this);
  this.manager=null;

  this.options=options;
  if(!this.options)
    this.options={};

  if(!this.options.title)
    this.options.title=t("unnamed");
  dom_create_append_text(this.header, this.options.title);

  if(!this.options.weight)
    this.options.weight=0;
}

function tab_manager(div) {
  // register_tab
  this.register_tab=function(tab) {
    this.tabs.push(tab);

    this.header.appendChild(tab.header);
    this.content.appendChild(tab.content);
    tab.manager=this;

    if(!this.active_tab)
      this.activate(tab);
  }

  // unregister_tab
  this.unregister_tab=function(tab) {
    var p=parseInt(array_search(tab, this.tabs));

    if(this.active_tab==tab) {
      this.deactivate(tab);

      if(p>=this.tabs.length-1)
	this.activate(this.tabs[p-1]);
      else
	this.activate(this.tabs[p+1]);
    }

    this.header.removeChild(tab.header);
    this.content.removeChild(tab.content);

    this.tabs=array_remove(this.tabs, p);
  }

  // show
  this.show=function() {
    var list=[];
    for(var i=0; i<this.tabs.length; i++) {
      list.push([ this.tabs[i].options.weight, this.tabs[i] ]);
    }
    list=weight_sort(list);

    for(var i=0; i<list.length; i++) {
      this.header.appendChild(list[i].header);
    }
  }

  // activate
  this.activate=function(tab) {
    if(this.active_tab)
      this.deactivate(this.active_tab);

    this.active_tab=tab;
    tab.header.className="tab_header_active";
    tab.content.className="tab_content_active";

    if(this.active_tab&&this.active_tab.on_activate)
      this.active_tab.on_activate();
  }

  // deactivate
  this.deactivate=function(tab) {
    if(this.active_tab!=tab)
      return;

    if(tab.on_deactivate)
      tab.on_deactivate();

    tab.header.className="tab_header";
    tab.content.className="tab_content";
  }
  
  // constructor
  this.tabs=[];
  this.active_tab=null;
  this.div=document.createElement("div");
  this.div.className="tab_manager";
  if(div)
    div.appendChild(this.div);
  this.content=document.createElement("div");
  this.content.className="tab_manager_content";
  this.header=document.createElement("div");
  this.header.className="tab_manager_header";
  this.div.appendChild(this.header);
  this.div.appendChild(this.content);
}

