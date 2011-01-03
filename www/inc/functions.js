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
