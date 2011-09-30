function talk(page, div) {
  // remove
  this.remove=function() {
  }

  // constructor
}

function talk_open_win(page) {
  var w=new win({ class: "talk", title: "Talk "+page });
  var t=new talk(page, w.content);
  w.onclose=t.remove.bind(t);
}
