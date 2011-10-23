// constructor button
function button(text, callback) {
  this.span=document.createElement("span");
  this.span.ob=this;
  this.span.className="button";

  if(is_dom(text))
    this.span.appendChild(text);
  else
    this.span.innerHTML=text;

  this.span.onclick=callback;
}

// button.dom
button.prototype.dom=function() {
  return this.span;
}

// button_dom
function button_dom(text, callback) {
  var b=new button(text, callback);
  return b.dom();
}

// constructor buttons
function buttons() {
  this.list=[];
  this.div=document.createElement("div");
  this.div.ob=this;
  this.div.className="buttons";
}

// buttons.add
buttons.prototype.add=function(entry) {
  this.list.push(entry);
  this.show();
}

// buttons.show
buttons.prototype.show=function() {
  var l=weight_sort(this.list);
  for(var i=0; i<l.length; i++) {
    this.div.appendChild(l[i]);
  }
}

// buttons.dom
buttons.prototype.dom=function() {
  return this.div;
}
