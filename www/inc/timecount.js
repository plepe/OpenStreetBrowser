var time_count_last=new Date().getTime();
var time_count_time=0;

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
}

register_hook("view_changed", time_count_active);
register_hook("view_changed_start", time_count_active);
register_hook("view_click", time_count_active);
register_hook("list_request", time_count_active);
register_hook("hash_changed", time_count_active);
