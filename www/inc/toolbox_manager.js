function _toolbox_manager() {
  // register
  this.register=function(toolbox) {
    toolbox.manager=this;
    this.toolboxes.push(toolbox);
    this.show_buttons();
  }

  // activate_toolbox
  this.activate_toolbox=function(index) {
    if(typeof index!="number") {
      for(var i=0; i<this.toolboxes.length; i++) {
	if(this.toolboxes[i]==index)
	  index=i;
      }
    }

    if(this.current_active>-1) {
      this.toolboxes[this.current_active].notify_deactivate();
      this.toolboxes[this.current_active].button.className="toolboxbutton";
    }

    if((this.current_active==index)||(index==-1)) {
      this.current_active=-1;
      return;
    }

    this.toolboxes[index].notify_activate();
    this.current_active=index;
    this.toolboxes[index].button.className="toolboxbutton_active";
  }

  // show_buttons
  this.show_buttons=function() {
    var toolbox_buttons=document.getElementById("toolboxbuttons_table");

    for(var i=0; i<this.toolboxes.length; i++) {
      var tb=this.toolboxes[i];
      tb.button.onclick=this.activate_toolbox.bind(this, i);
      tb.button.className="toolboxbutton";
    }

    var newtds=[];
    var min_wgt=100;
    while(newtds.length!=this.toolboxes.length) {
      var min_tds=[];

      for(var i=0; i<this.toolboxes.length; i++) {
	var tb=this.toolboxes[i];
	var wgt=tb.options.weight;

	if(tb.options.weight<min_wgt) {
	  min_wgt=tb.options.weight;
	  min_tds=[];
	}
	if(tb.options.weight==min_wgt) {
	  min_tds.push(tb);
	}
      }

      newtds=newtds.concat(min_tds);
      min_tds=tb.options.weight-1;
    }

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
