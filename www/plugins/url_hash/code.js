var url_hash_last_location_hash;
function url_hash_check_redraw() {
  var new_hash=location.hash;

  // some browsers do not decode special characters
  new_hash=urldecode(new_hash);

  if(new_hash!=url_hash_last_location_hash) {
    location_params={};

    var m;
    if(m=new_hash.match(/^#(.*)\?(.*)$/)) {
      location_params=string_to_hash(m[2]);
      if(m[1]!="")
	location_params.obj=m[1];
      call_hooks("recv_permalink", hash_to_string(location_params));
    }
    else if(new_hash.substr(0, 1)=="#") {
      location_params.obj=new_hash.substr(1);
    }

    call_hooks("hash_changed", location_params);
    url_hash_last_location_hash=new_hash;
  }

  redraw_timer=setTimeout("url_hash_check_redraw()", 300);
}

function url_hash_init() {
  if(!location.hash) {
    location.hash="#";
  }

  redraw_timer=setTimeout("url_hash_check_redraw()", 300);
}

register_hook("init", url_hash_init);
