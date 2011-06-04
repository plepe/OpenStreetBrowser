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

function blog_show(data) {
  var blog_rss=data.responseXML;
  var rss=blog_read(blog_rss);

  dom_clean(blog_content);

  for(var i=0; i<rss.length; i++) {
    var item_head=dom_create_append(blog_content, "h2");
    item_head.className="blog";

    var a=dom_create_append(item_head, "a");
    a.href=rss[i].link;
    a.target="_new";
    a.innerHTML=rss[i].title;

    var item_info=dom_create_append(blog_content, "div");
    var d=new Date(rss[i]['pubDate']);
    item_info.innerHTML=strftime("%Y-%m-%d %H:%m UTC", d)+" by "+
      rss[i]['dc:creator'];

    var item_cont=dom_create_append(blog_content, "div");
    item_cont.innerHTML=rss[i].description;
  }
}

function blog_hide() {
  blog_win.close();
}

function blog_create_win() {
  blog_win=new win("blog_win");
  blog_win.content;

  var h1=dom_create_append(blog_win.content, "h1");
  h1.className="blog";
  dom_create_append_text(h1, lang('blog:header'));

  blog_content=dom_create_append(blog_win.content, "div");
  blog_content.className="blog_content";
  blog_content.innerHTML="<img src='img/ajax_loader.gif' /> "+lang("loading")+"</div>\n";

  var close=dom_create_append(blog_win.content, "input");
  close.type="button";
  close.value="Close";
  close.onclick=blog_hide;

  var visit=dom_create_append(blog_win.content, "a");
  visit.href="http://blog.openstreetbrowser.org/";
  visit.target="_new";
  visit.innerHTML=lang("blog:visit");
}

function blog_init() {
  blog_create_win();

  ajax_direct("plugins/blog/rss.php", null, blog_show);
}

register_hook("init", blog_init);
