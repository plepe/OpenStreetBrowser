var blog_win;
var blog_content;

function blog_read(rss) {
  var ret=[];

  var channel=rss.firstChild.firstChild;
  while(channel) {
    if(channel.nodeName=="channel") {
      item=channel.firstChild;
      while(item) {
	if(item.nodeName=="item") {
	  i=item.firstChild;
	  data={};
	  while(i) {
	    data[i.nodeName]=i.textContent;
	    i=i.nextSibling;
	  }

	  ret.push(data);
	}

	item=item.nextSibling;
      }
    }

    channel=channel.nextSibling;
  }

  return ret;
}

function blog_check_new(rss) {
  if(rss.length>0) {
    var newest_item=rss[0];
    var blog_last_guid=cookie_read("blog_last_guid");

    if(blog_last_guid==newest_item.guid)
      return false;

    cookie_write("blog_last_guid", newest_item.guid);
  }

  return true;
}

function blog_foo() {
  blog_loader.parentNode.removeChild(blog_loader);
}

function blog_show(rss) {
}

function blog_hide() {
  blog_win.close();
}

function blog_create_win() {
  blog_win=new win({ class: "blog_win", title: lang('blog:header') });
  blog_win.content;

  blog_content=dom_create_append(blog_win.content, "div");
  blog_content.className="blog_content";

  var i=dom_create_append(blog_content, "iframe");
  i.src="http://blog.openstreetbrowser.org/?q=frame";
  i.onload=blog_foo;

  blog_loader=dom_create_append(blog_win.content, "div");
  blog_loader.className="blog_loader";
  blog_loader.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading")+"</div>\n";

  var close=dom_create_append(blog_win.content, "input");
  close.type="button";
  close.value="Close";
  close.onclick=blog_hide;

  var visit=dom_create_append(blog_win.content, "a");
  visit.href="http://blog.openstreetbrowser.org/";
  visit.target="_new";
  visit.innerHTML=lang("blog:visit");
}

function blog_show_startup(data) {
  var blog_rss=data.responseXML;
  var rss=blog_read(blog_rss);

  if(!blog_check_new(rss))
    return;

  blog_create_win();
}

function blog_show_menu(data) {
  ajax_direct(modulekit_file("blog", "rss.php"), null, blog_show_menu_next);
  blog_create_win();
}

function blog_show_menu_next(data) {
  var blog_rss=data.responseXML;
  var rss=blog_read(blog_rss);

  blog_show(rss);
}

function blog_init() {
  ajax_direct(modulekit_file("blog", "rss.php"), null, blog_show_startup);
}

register_hook("init", blog_init);
