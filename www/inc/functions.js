function hash_to_string(ar) {
  var ret=[];

  for(var i in ar) {
    ret.push(i+"="+ar[i]);
  }

  return ret.join("&");
}

function string_to_hash(str) {
  var ret={};
  var ar=str.split(/&/);
  for(var i=0; i<ar.length; i++) {
    var x=ar[i].split(/=/);
    var k=x[0];
    x.shift();
    ret[k]=x.join("=");
  }

  return ret;
}

// weight_sort(arr)
// Parameters:
// arr ... an array of form [ [ weight, var], ... ]
//         [ [ -3, A ], [ -1, B ], [ 5, C ], [ -1, D ] ]
//         
// Returns:
// An array sorted by the weight of the source, e.g.
//         [ A, B, D, C ]
//
// Notes:
// Entries in the source array with the same weight are returned in the
// same order
function weight_sort(arr) {
  function numerical_cmp(a, b) {
    return a-b;
  }

  var ret1={};

  // first put all elements into an assoc. array
  for(var i=0; i<arr.length; i++) {
    var cur=arr[i];
    var wgt=cur[0];

    if(!ret1[wgt])
      ret1[wgt]=[];

    ret1[wgt].push(cur[1]);
  }

  // get the keys, convert to value, order them
  var keys1=keys(ret1);
  keys1.sort(numerical_cmp);
  var ret2=[];

  // iterate through array and compile final return value
  for(var i=0; i<keys1.length; i++) {
    for(var j=0; j<ret1[keys1[i]].length; j++) {
      ret2.push(ret1[keys1[i]][j]);
    }
  }

  return ret2;
}

function dom_create_append(parent, type, xml) {
  if(!xml)
    xml=document;

  var x=xml.createElement(type);
  parent.appendChild(x);
  return x;
}

function dom_create_append_text(parent, text, xml) {
  if(!xml)
    xml=document;

  var x=xml.createTextNode(text);
  parent.appendChild(x);
  return x;
}

function dom_clean(parent) {
  while(parent.firstChild)
    parent.removeChild(parent.firstChild);
}
