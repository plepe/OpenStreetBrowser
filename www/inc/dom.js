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

