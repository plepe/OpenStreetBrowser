var time_count_last=new Date().getTime();
var time_count_time=0;
var time_count_beg=0;

function time_count_check_cookie() {
  var cookie;
  if(cookie=document.cookie) {
    var cookies=cookie.split(/;/);

    for(var i=0; i<cookies.length; i++) {
      var m;

      if(m=cookies[i].match(/time_count=([0-9]+),([0-9]+)/)) {
	time_count_time=parseInt(m[1]);
	time_count_beg=parseInt(m[2]);
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

  var expiry=new Date();
  expiry.setTime(expiry.getTime()+30*86400000);
  document.cookie='time_count='+time_count_time+","+time_count_beg+"; expires="+expiry.toGMTString()+"; path=/";

  time_count_check_beg();
}

function time_count_do_beg() {
  var div=document.createElement("iframe");

  document.body.appendChild(div);
  div.className="beg";
  div.id="donation";

  div.style.position="absolute";
  div.style.left=(window.innerWidth/2-250)+"px";
  div.style.right=(window.innerWidth/2+250)+"px";
  div.style.top=(window.innerHeight/2-200)+"px";
  div.style.bottom=(window.innerHeight/2+200)+"px";

  div.src="donate.php";

  time_count_beg=time_count_time;
}

function time_count_check_beg() {
  if((time_count_beg==0)&&(time_count_time>15000*60)) {
    time_count_do_beg();
  }

  if(time_count_time-time_count_beg>2*60*60*1000) {
    time_count_do_beg();
  }
}

function close_donation() {
  var div=document.getElementById("donation");

  div.parentNode.removeChild(div);
}

register_hook("view_changed", time_count_active);
register_hook("view_changed_start", time_count_active);
register_hook("view_click", time_count_active);
register_hook("list_request", time_count_active);
register_hook("hash_changed", time_count_active);
