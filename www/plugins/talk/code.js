function talk(page, div) {
  // remove
  this.remove=function() {
  }

  // load
  this.load=function() {
    ajax("talk_load", { page: this.page }, this.load_callback.bind(this));
  }

  // load_callback
  this.load_callback=function(ret) {
    ret=ret.return_value;

    this.div.appendChild(creole(ret));
  }

  // constructor
  this.page=page;
  this.div=div;

  this.load();
}

function talk_open_win(page) {
  var w=new win({ class: "talk", title: "Talk "+page });
  var t=new talk(page, w.content);
  w.onclose=t.remove.bind(t);
}
