/* ajax.js
 * - JavaScript code that is used globally
 *
 * Copyright (c) 1998-2006 Stephan Plepelits <skunk@xover.mud.at>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
var last_params;

function ajax(funcname, param, _callback) {
  // private
  this.xmldata;
  // public
  var req=false;
  var callback;

  function req_change() {
    if(req.readyState==4) {
      this.xmldata=req.responseXML;

      if(callback)
        callback(req);
    }
  }

  // branch for native XMLHttpRequest object
  if(window.XMLHttpRequest) {
    try {
      req = new XMLHttpRequest();
    }
    catch(e) {
      req = false;
    }
    // branch for IE/Windows ActiveX version
  } else if(window.ActiveXObject) {
    try {
      req = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
      try {
        req = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e) {
        req = false;
      }
    }
  }

  if(req) {
    var p=new Array();
    ajax_build_request(param, "param", p);
    p=p.join("&");

    callback=_callback;
    req.onreadystatechange = req_change;
    req.open("GET", "ajax.php?func="+funcname+"&"+p, 1);
    last_params=p;
    req.send("");
  }
}

function ajax_call(funcname, param) {
  // private
  this.xmldata;
  // public
  var req=false;
  var callback;

  // branch for native XMLHttpRequest object
  if(window.XMLHttpRequest) {
    try {
      req = new XMLHttpRequest();
    }
    catch(e) {
      req = false;
    }
    // branch for IE/Windows ActiveX version
  } else if(window.ActiveXObject) {
    try {
      req = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
      try {
        req = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e) {
        req = false;
      }
    }
  }

  if(req) {
    var p=new Array();
    ajax_build_request(param, "param", p);
    p=p.join("&");

    req.open("GET", "ajax.php?func="+funcname+"&"+p, false);
    last_params=p;
    req.send("");

    var xml=req.responseXML;
    if(!xml)
      return req;

    var ret=xml.getElementsByTagName("return");
    if(ret.length) {
      var ret=json_parse(ret[0].firstChild.nodeValue);
      return ret;
    }

    return req;
  }
}

function ajax_direct(url, param, _callback) {
  // private
  this.xmldata;
  // public
  var req=false;
  var callback;

  function req_change() {
    if(req.readyState==4) {
      this.xmldata=req.responseXML;

      if(callback)
        callback(req);
    }
  }

  // branch for native XMLHttpRequest object
  if(window.XMLHttpRequest) {
    try {
      req = new XMLHttpRequest();
    }
    catch(e) {
      req = false;
    }
    // branch for IE/Windows ActiveX version
  } else if(window.ActiveXObject) {
    try {
      req = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
      try {
        req = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e) {
        req = false;
      }
    }
  }

  if(req) {
    var p=new Array();
    ajax_build_request(param, null, p);
    p=p.join("&");

    callback=_callback;
    req.onreadystatechange = req_change;
    req.open("GET", url+"?"+p, 1);
    last_params=p;
    req.send("");
  }
}

function ajax_read_formated_text(xmldata, key) {
  ret="";

  obs=xmldata.getElementsByTagName(key);
  for(i=0; i<obs.length; i++) {
    ret+=obs[i].firstChild.nodeValue;
  }

  return ret;
}

function ajax_read_value(xmldata, key) {
  ob=xmldata.getElementsByTagName(key);
  if(!ob)
    return null;
  if(ob.length==0)
    return "";
  if(!ob[0].firstChild)
    return "";

  var text=ajax_read_formated_text(xmldata, key)
//console.log(text);
  var x=new Function("return "+text+";");
  //ob[0].firstChild.nodeValue+";");
  return x();
}

function ajax_build_request(param, prefix, ret) {
  if(typeof param=="object") {
    for(var k in param) {
      if(!prefix)
	ajax_build_request(param[k], k, ret);
      else
	ajax_build_request(param[k], prefix+"["+k+"]", ret);
    }
  }
  else if(typeof param=="number") {
    ret.push(prefix+"="+String(param));
  }
  else if(typeof param=="string") {
    ret.push(prefix+"="+param);
  }
  else if(typeof param=="undefined") {
    ret.push(prefix+"="+0);
  }
  else if(typeof param=="function") {
    // ignore functions
  }
  else {
    alert("not supported var type: "+typeof param);
  }
}

function set_session_vars(vars, callback) {
  var params=new Array();

  for(var i in vars) {
    params.push("var["+i+"]="+vars[i]);
  }

  params=params.join("&");
  start_xmlreq(url_script({script: "toolbox.php", todo: "set_session_vars" })+"&"+params, 0, callback);
}

function get_content(ob) {
  if(ob.text)
    return ob.text;
  else
    return ob.textContent;
}

function ajax_post(url, getparam, postdata, _callback) {
  // private
  this.xmldata;
  // public
  var req=false;
  var callback;

  function req_change() {
    if(req.readyState==4) {
      this.xmldata=req.responseXML;

      if(callback)
        callback(req);
    }
  }

  // branch for native XMLHttpRequest object
  if(window.XMLHttpRequest) {
    try {
      req = new XMLHttpRequest();
    }
    catch(e) {
      req = false;
    }
    // branch for IE/Windows ActiveX version
  } else if(window.ActiveXObject) {
    try {
      req = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
      try {
        req = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e) {
        req = false;
      }
    }
  }

  if(req) {
    var p=new Array();
    ajax_build_request(getparam, null, p);
    p=p.join("&");

    callback=_callback;
    req.onreadystatechange = req_change;
    req.open("POST", url+"?"+p, 1);

    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.setRequestHeader("Content-length", postdata.length);
    req.setRequestHeader("Connection", "close");

    req.send(postdata);
  }
}


