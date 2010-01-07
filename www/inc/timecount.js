var time_count_last=new Date().getTime();
var time_count_time=0;

function time_count_check_cookie() {
  var cookie;
  if(cookie=document.cookie) {
    var cookies=cookie.split(/;/);

    for(var i=0; i<cookies.length; i++) {
      var m;

      if(m=cookies[i].match(/time_count=([0-9]+)/)) {
	time_count_time=parseInt(m[1]);
      }
    }
  }
}

time_count_check_cookie();

function time_count_active() {
  var now=new Date().getTime();

  if(time_count_last) {
    if(now-time_count_last>5000)
      time_count_time+=5000;
    else
      time_count_time+=now-time_count_last;
  }

  time_count_last=now;

  var ob=document.getElementById("lang_select");
  ob.innerHTML=time_count_time;

  var expiry=new Date();
  expiry.setTime(expiry.getTime()+30*86400000);
  document.cookie='time_count='+time_count_time+"; expires="+expiry.toGMTString()+"; path=/";
}

register_hook("view_changed", time_count_active);
register_hook("view_changed_start", time_count_active);
register_hook("view_click", time_count_active);
register_hook("list_request", time_count_active);
register_hook("hash_changed", time_count_active);
