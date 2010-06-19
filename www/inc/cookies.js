//Use these functions for reading, writing and deleting cookies!

function cookie_read(cookiename) {
  var cookies=document.cookie.split("; ");
  for(var i=0;i<cookies.length;i++) {
    var a=cookies[i].split("=");
    if(a[0] == cookiename) {
      a.shift();
      return a.join("=");
    }
  }
  return null;
}

function cookie_write(cookiename, cookievalue) {
  var expiry=new Date();
  expiry.setTime(expiry.getTime()+365*86400000);
  document.cookie=cookiename+"="+cookievalue+"; expires="+expiry.toGMTString()+"; path=/";
}

function cookie_delete(cookiename) {
  document.cookie = cookiename+"=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/";
}
