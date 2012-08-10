function url_history_statechange() {
  var state=window.History.getState();
  var m;

  new_hash=state.url.substr(url_historyjs_base_url.length);

  if(m=new_hash.match("#(.*)$")) {
    new_hash=m[1];
  }

  if(m=new_hash.match(/^\.(\?.*)$/))
    new_hash=m[1];

  if(new_hash==".")
    new_hash="";

  location_params={};

  var m;
  if(m=new_hash.match(/^(.*)\?(.*)$/)) {
    location_params=string_to_hash(m[2]);
    location_params.obj=m[1];
    call_hooks("recv_permalink", hash_to_string(location_params));
  }
  else {
    location_params.obj=new_hash;
  }

  call_hooks("hash_changed", location_params);
}

function url_history_anchorchange() {
  var loc=location.hash.substr(1);
  window.History.replaceState(null, null, loc);
}

function url_history_follow_link(ob) {
  var baseurl=get_baseurl();
  var m;

  var href=ob.getAttribute("href");
  if(!href)
    return;

  if(href.substr(0, 11)=="javascript:")
    return true;

  if(m=href.match(/#(.*)$/)) {
    var hash=m[1];
    if(hash=="")
      hash=".";
    window.History.pushState(null, null, hash);
    return false;
  }

  if(!href.match(/^https?:\/\//)) {
    if(href.match(/^\?(.*)$/))
      href="."+href;
    window.History.pushState(null, null, href);
    return false;
  }

  return true;
}

function url_history_check_link(ob) {
  var m;
  var href=ob.getAttribute("href");

  if(!ob.onclick)
    ob.onclick=url_history_follow_link.bind(this, ob);

  if(!href)
    return;

  if(m=href.match(/^#(.*)$/))
    href=m[1];

  if(m=href.match(/^([a-zA-Z_0-9:=\.,]*\?.*)$/))
    ob.setAttribute("href", "."+href);
}

function url_history_content_change2(ob) {
  if(ob.tagName=="A") {
    url_history_check_link(ob);
  }

  var curr=ob.firstChild;
  while(curr) {
    url_history_content_change2(curr);
    curr=curr.nextSibling;
  }
}

function url_history_content_change(event) {
  if(!event)
    return;

  if(!event.target.tagName)
    return;

  url_history_content_change2(event.target);
}

function url_historyhs_init() {
  // if the state changes call this function
  window.History.Adapter.bind(window, "statechange", url_history_statechange);
  window.History.Adapter.bind(window, "anchorchange", url_history_anchorchange);

  // if new nodes are inserted to the tree call this function
  // modify all links there
  document.addEventListener("DOMNodeInserted", url_history_content_change);

  // modify all links currently in the dom tree
  var as=document.getElementsByTagName("a");
  for(var i=0; i<as.length; i++) {
    url_history_check_link(as[i]);
  }

  // if there's a # in the anchor remove it
  if(location.hash!="") {
    url_history_anchorchange();
    return;
  }
  
  new_hash=get_baseurl();
  new_hash=new_hash.substr(url_historyjs_base_url.length);
  window.History.pushState(null, null, new_hash);
  url_history_statechange();
}

function url(params, full) {
  var ret="";
  var base="";

  if(full)
    base=url_historyjs_base_url;

  if(!params)
    return base;

  if(typeof params != "object")
    return base+urlencode(params);

  if(params.obj) {
    ret+=urlencode(params.obj);
    delete params.obj;
  }

  if(keys(params).length) {
    ret+="?";

    for(var k in params) {
      ret+=urlencode(k)+"="+urlencode(params[k])+"&";
    }

    ret=ret.substr(0, ret.length-1);
  }

  return base+ret;
}

function set_url(params) {
  var loc=url(params);
  window.History.replaceState(null, null, loc);
}

register_hook("post_init", url_historyhs_init);
