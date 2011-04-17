// list - shows a list of objects in a div
// * parameters:
// div - a domnode of the div, where the list should be displayed
// elements - an array of elements, described later. add a 'null' value as last
//   element to indicate further elements
// request_more - an (optional) function, which will be called when more
//   elements are needed. Either return an array directly or return 0 and call
//   function recv later to add more elements. set the last value 'null' to
//   indicate more elements.
// options - a hash array of additional options:
//   show_count: amount of elements to show before 'more'
//   empty_text: text to indicate empty list (default: lang('nothing_found') )
//   loading_text: text to indicate loading (default: lang('loading') )
// * properties
//   shown - How many elements are shown right now
//   should_shown - How many elements should be shown right now (waiting for
//     recv)
//
// elements: An array of hash arrays, looking like this (see next paragraph):
// [ { name: 'The Old Pub', href='#node_1234', icon: 'pub' }, ..., null ]
//
// possible attributes for each entry:
// .name ... (string) Name of object
// .href ... (string) href to object
// .icon ... (string) An icon. see get_icon()
// .type ... (string) A type, write in brackets after name
// .title .. (string) A tooltip for the name
// .highlight ... (string/array of strings) A WKT of the geometric object
// .highlight_center ... (string) A WKT of the center of the geometric
//                       object(s)

var list_default_options;

function list(div, elements, request_more, options) {
  // recv - receive more elements
  // * parameters:
  // elements - an array of elements
  this.recv=function(more_elements) {
    if(this.elements[this.elements.length-1]==null) {
      this.elements=this.elements
        .slice(0, this.elements.length-1)
        .concat(more_elements);
    }

    this.show(this.elements);
  }

  // show_element
  this.show_element=function(element) {
    var li=dom_create_append(this.ul, "li");
    li.className="list";

    // icon
    if(element.icon) {
      var x=get_icon(element.icon);
      li.style.listStyleImage="url('"+x.icon_url()+"')";
    }
    else if(element.icon_url) {
      li.style.listStyleImage="url('"+element.icon_url+"')";
    }

    // href
    var a=dom_create_append(li, "a");
    if(element.href)
      a.href=element.href;

    // title
    if(element.title)
      a.title=element.title;

    // name
    dom_create_append_text(a, element.name);

    // type
    if(element.type)
      dom_create_append_text(li, " ("+element.type+")");

    // highlight
    if(element.highlight) {
      li.onmouseover=this.set_highlight.bind(this, element);
      li.onmouseout=this.unset_highlight.bind(this, element);
    }
  }

  // more
  this.more=function() {
    this.should_shown+=this.options.show_count;
    this.show();
  }

  // show
  this.show=function() {
    while((this.elements.length>this.shown)&&
          (this.elements[this.shown]!=null)&&
          (this.shown<this.should_shown)) {
        this.show_element(this.elements[this.shown]);
        this.shown++;
    }

    dom_clean(this.div_more);
    if(this.elements.length==0) {
      this.div_more.innerHTML=this.options.empty_text;
    }
    else if(this.elements.length==this.shown) {
      // no more, done
    }
    else if(this.elements.length>this.should_shown) {
      // more, done

      var a=dom_create_append(this.div_more, "a");
      a.onclick=this.more.bind(this);
      dom_create_append_text(a, lang("more"));
    }
    else if(this.elements[this.shown]==null) {
      // we need more
      this.div_more.innerHTML="<img class='loading' src='img/ajax_loader.gif'> "+this.options.loading_text;
      var more=request_more(this.elements);
      if(more)
        this.recv(more);
    }
  }

  // set_highlight
  this.set_highlight=function(element) {
    if(!element.ob_highlight)
      element.ob_highlight=new highlight(element.highlight, element.highlight_center);

    if(element.ob_highlight)
      element.ob_highlight.show();
  }

  // unset highlight
  this.unset_highlight=function(element) {
    if(element.ob_highlight)
      element.ob_highlight.hide();
  }

  // constructor
  this.options=options;
  if(!options)
    this.options={};
  for(var i in list_default_options) {
    if(!this.options[i])
      this.options[i]=list_default_options[i];
  }

  this.shown=0;
  this.should_shown=this.options.show_count;
  this.elements=elements;
  this.div=div;
  this.ul=dom_create_append(div, "ul");
  this.div_more=dom_create_append(div, "div");
  this.show();
}

function list_init() {
  list_default_options={
    show_count: 10,
    empty_text: lang('nothing_found'),
    loading_text: lang('loading')
  };
}

register_hook("init", list_init);
