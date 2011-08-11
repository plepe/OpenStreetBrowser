function category_window(category) {
  // constructor
  this.win=new win("category_window");
  this.win.content.innerHTML="loading";
  this.category=category;

  call_hooks("category_window_show", this, category);
}

function category_window_start_window(category) {
  new category_window(category);
}

function category_window_write_div(div, category) {
  // write_div
  var span=dom_create_append(div.header, "span");
  span.className="category_tools";
  div.header.insertBefore(span, div.header.firstChild);

  var img=dom_create_append(span, "img");
  img.className="category_tools";
  img.onclick=category_window_start_window.bind(null, category);
  img.src="plugins/category_window/info.png";
}

register_hook("category_write_div", category_window_write_div);
