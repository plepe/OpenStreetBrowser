// info - shows details of an object (ob) in the sidebar
// * Parameter:
// ob is an instance of class geo_object
//
// * Functions:
// .show() - Show information about the object in the sidebar
// .hide() - Hide the information
//
// * Hooks:
// info calls the following hooks:
// "info" (chapters[], object)
//   please add one or more chapter(s) to the chapters array like this:
//   example: { head: 'tags', weight: 5, content: dom node }
//    .head: if there are several chapters with the same head, they will be
//      concatenated to one at the position of the highest weight.
//    .weight: The higher the weight, the lower the position of the content
//    .content: A string with the text, which will be added to a new div or a
//      dom node, which will be append the the chapter (recommended)
//   object is the reference to the geo object
//
// example: { head: "actions", weight: 5, content: "Some Action")
function info(ob) {
  // get_data
  this.get_data=function() {
    // get chapters
    this.chapters=[];

    if(this.ob.id) {
      ajax("info", this.ob.id, this.get_data_callback.bind(this));
    }

    this.ob.info(this.chapters);
    call_hooks("info", this.chapters, this.ob);

    this.show();
  }


  // get_data_callback
  this.get_data_callback=function(response) {
    var data=response.return_value;

    if(data===null) {
      alert("no data\n"+response.responseText);
      return;
    }

    this.chapters=this.chapters.concat(data);

    this.show();
  }

  // show
  this.show=function() {
    var ret="";
    var info_content=document.getElementById("details_content");
    dom_clean(info_content);

    this.div=dom_create_append(info_content, "div");
    this.div.className="object";

    // header
    var h1=dom_create_append(this.div, "h1");
    dom_create_append_text(h1, ob.name());

    // interaction
    var actions=dom_create_append(this.div, "div");
    var a=dom_create_append(actions, "a");
    a.className="zoom";
    a.href="#";
    a.onclick=redraw;
    dom_create_append_text(a, lang("info_back"));

    if(ob.geo) {
      var a=dom_create_append(actions, "a");
      a.className="zoom";
      a.onclick=this.zoom_to_feature.bind.this();
      dom_create_append_text(a, lang("info_back"));
    }

    var data=merge_chapters(this.chapters);

    this.div_chapter={};
    for(var i=0; i<data.length; i++) {
      var head="";
      if(data[i][0].head)
        head=data[i][0].head;

      this.div_chapter[head]=dom_create_append(this.div, "div");
      if(head) {
        var h2=dom_create_append(this.div_chapter[head], "h2");
        dom_create_append_text(h2, lang("head:"+head));
      }

      for(var j=0; j<data[i].length; j++) {
        if(!data[i][j].content) {
          var div=dom_create_append(this.div_chapter[head], "div");
          data[i][j].content_node=div;
        }
        else if(typeof data[i][j].content=="string") {
          var div=dom_create_append(this.div_chapter[head], "div");
          div.innerHTML=data[i][j].content;
          data[i][j].content_node=div;
        }
        else {
          this.div_chapter[head].appendChild(data[i][j].content);
          data[i][j].content_node=data[i][j].content;
        }
      }
    }

    this.data=data;
  }

  // hide
  this.hide=function() {
  }

  // constructor
  this.ob=ob;
  this.get_data();
  this.show();
}

function merge_chapters(chapters) {
  var sort_chapters={};
  for(var i=0; i<chapters.length; i++) {
    if(!chapters[i])
      continue;

    var w=chapters[i].weight;
    if(!w)
      w=0;
    var h=chapters[i].head;
    if(!h)
      h="";

    if(sort_chapters[h]) {
      sort_chapters[h][1].push([ w, chapters[i] ]);
      if(w<sort_chapters[h][0])
        sort_chapters[h][0]=w;
    }
    else {
      sort_chapters[h]=[ w, [ [ w, chapters[i] ] ] ];
    }
  }

  var c=[];
  for(var i in sort_chapters)
    c.push(sort_chapters[i]);
  sort_chapters=c;

  sort_chapters=weight_sort(sort_chapters);
  for(var i=0; i<sort_chapters.length; i++) {
    sort_chapters[i]=weight_sort(sort_chapters[i]);
  }

  return sort_chapters;
}

// deprecated code
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
