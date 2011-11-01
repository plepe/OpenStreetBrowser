function dom_create_append(parent, type, xml) {
  if(!parent) {
    alert("dom_create_append: no parent!");
    return;
  }

  if(!xml)
    xml=document;

  var x=xml.createElement(type);
  parent.appendChild(x);
  return x;
}

function dom_create_append_text(parent, text, xml) {
  if(!parent) {
    alert("dom_create_append_text: no parent!");
    return;
  }

  if(!xml)
    xml=document;

  var x=xml.createTextNode(text);
  parent.appendChild(x);
  return x;
}

function dom_clean(parent) {
  if(!parent) {
    alert("dom_clean: no parent!");
    return;
  }

  while(parent.firstChild)
    parent.removeChild(parent.firstChild);
}

// Source: http://www.questionhub.com/StackOverflow/384286, Commeny by Greg
function is_dom(obj) {
  var elm = document.createElement('div');
  try {
    elm.appendChild(obj);
  }
  catch (e) {
    return false;
  }

  return true;
}

// gets right distance from root-node
function dom_distance_right(obj, root) {
  if(!root)
    root=document.body;
  if(obj==root)
    return 0;
  if(root.offsetParent==obj)
    return 0;

  var p=obj.offsetParent;
  var ret=p.clientWidth-
          (obj.offsetLeft+obj.offsetWidth)+
	  dom_distance_right(p, root);

  return ret;
}
