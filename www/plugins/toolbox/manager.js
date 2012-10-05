function _toolbox_manager() {
  // register
  this.register=function(toolbox) {
    toolbox.manager=this;
    this.toolboxes.push(toolbox);
    this.show_buttons();
  }

  // unregister
  this.unregister=function(toolbox) {
    toolbox.manager=this;
    var p=array_search(toolbox, this.toolboxes);
    if(p!==false) {
      var toolbox_buttons=document.getElementById("toolboxbuttons_table");
      toolbox_buttons.rows[0].removeChild(toolbox.button);

      this.toolboxes=array_remove(this.toolboxes, p);
      this.show_buttons();
    }
  }

  // activate_toolbox
  this.activate_toolbox=function(index,force) {
    if(typeof index!="number") {
      for(var i=0; i<this.toolboxes.length; i++) {
	if(this.toolboxes[i]==index)
	  index=i;
      }
    }

    if(this.current_active>-1) {
      this.toolboxes[this.current_active].notify_deactivate();
      this.toolboxes[this.current_active].button.className="toolboxbutton";
      this.toolboxes[this.current_active].content_container.className="toolbox";
    }

    if((force!=1)&&((this.current_active==index)||(index==-1))) {
      this.current_active=-1;
      this.resize_toolbox();
      return;
    }

    this.current_force=force;

    this.current_active=index;
    this.toolboxes[index].notify_activate();
    this.toolboxes[index].button.className="toolboxbutton_active";
    this.toolboxes[index].content_container.className="toolbox_active";
    this.resize_toolbox();
  }

  // resize_toolbox
  this.resize_toolbox=function() {
    window_resize();
  }

  // show_buttons
  this.show_buttons=function() {
    var toolbox_buttons=document.getElementById("toolboxbuttons_table");
    var toolbox_divs=document.getElementById("toolbox_container");

    for(var i=0; i<this.toolboxes.length; i++) {
      var tb=this.toolboxes[i];
      tb.button.onclick=this.activate_toolbox.bind(this, i);
      toolbox_divs.appendChild(tb.content_container);
    }

    var sorttds=[];
    for(var i=0; i<this.toolboxes.length; i++) {
      var tb=this.toolboxes[i];
      sorttds.push([ tb.options.weight, tb ]);
    }
    var newtds=weight_sort(sorttds);
    
    for(var i=0; i<newtds.length; i++) {
      toolbox_buttons.rows[0].appendChild(newtds[i].button);
    }
  }

  // constructor
  this.toolboxes=[];
  this.current_active=-1;
}

var toolbox_manager=new _toolbox_manager();

function register_toolbox(toolbox) {
  return toolbox_manager.register(toolbox);
}

function unregister_toolbox(toolbox) {
  return toolbox_manager.unregister(toolbox);
}
