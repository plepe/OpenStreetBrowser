function url_history_statechange() {
  var state=window.History.getState();
  var m;

  new_hash=state.url.substr(url_historyjs_base_url.length);

  if(m=new_hash.match("#(.*)$")) {
    new_hash=m[1];
  }

  location_params={};

  var m;
  if(m=new_hash.match(/^(.*)\?(.*)$/)) {
    location_params=string_to_hash(m[2]);
    if(m[1]!="")
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

  if(ob.href.substr(0, 11)=="javascript:")
    return true;

  if(m=ob.href.match(/#(.*)$/)) {
    var hash=m[1];
    window.History.pushState(null, null, hash);
    return false;
  }

  if(ob.href.substr(0, baseurl.length)==baseurl) {
    var hash=ob.href.substr(baseurl.length);
    window.History.pushState(null, null, hash);
    return false;
  }

  return true;
}

function url_history_content_change(event) {
  var m;

  if(!event)
    return;

  if(!event.target.tagName)
    return;

  if(event.target.tagName=="A") {
    if(!event.target.onclick)
      event.target.onclick=url_history_follow_link.bind(this, event.target);

    if(m=event.target.href.match(/^#(.*)$/))
      event.target.href=m[1];
  }
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
    var a=as[i];

    if(!a.onclick) {
      a.onclick=url_history_follow_link.bind(this, a);
    }
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

register_hook("post_init", url_historyhs_init);
