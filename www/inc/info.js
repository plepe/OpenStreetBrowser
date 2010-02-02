var info_no_show=["routing", "internal"];

function info_change() {
  var form=document.forms.details_content;
  var el=form.getElementsByTagName("input");
  for(var i=0; i<el.length; i++) {
    if(el[i].type=="checkbox") {
      if(el[i].checked) {
	for(var j=0; j<info_no_show.length; j++) {
	  if(info_no_show[j]==el[i].name) {
	    var saved;
	    saved=info_no_show.slice(j+1);
	    info_no_show.splice(j, info_no_show.length-j);
	    info_no_show=info_no_show.concat(saved);
	  }
	}
      }
      else {
	var found=0;
	for(var j=0; j<info_no_show.length; j++) {
	  if(info_no_show[j]==el[i].name)
	    found=1;
	}
	if(!found)
	  info_no_show.push(el[i].name);
      }
    }
  }

  redraw();
}

function info_noshow(param) {
  param["info_noshow"]=info_no_show.join(",");
}

register_hook("request_details", info_noshow);
