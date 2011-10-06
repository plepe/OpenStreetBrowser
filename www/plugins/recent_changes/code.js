// return format for recent_changes hook:
// [ { date: '2011-12-13 14:15:16', user: 'test', msg: 'changed bla', plugin: 'talk', 'href': link to page }, ... ]
//
function recent_changes() {
  // close
  this.remove=function() {
  }

  // load
  this.load=function() {
    ajax("recent_changes_load", null, this.load_callback.bind(this));
    call_hooks("recent_changes_load", this.list);
  }

  // load_callback
  this.load_callback=function(ret) {
    ret=ret.return_value;

    this.list=this.list.concat(ret);
  }

  // constructor
  this.win=new win({ class: "recent_changes", title: lang("recent_changes:name") });
  this.onclose=this.remove.bind(this);
  this.load();
}

function recent_changes_show() {
  new recent_changes();
}
