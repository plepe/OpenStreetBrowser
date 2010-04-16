function split_semicolon(str) {
  var x=str.split(/;/);
  var ret=new Array();

  for(var i=0; i<x.length; i++) {
    if(x[i].substr(0, 1)=="\"") {
      var j=i;
      while(x[j].substr(-1)!="\"")
	j++;
      var y=x.slice(i, j+1).join(";");
      ret.push(y.substr(1, y.length-2));
      i=j;
    }
    else
      ret.push(x[i]);
  }

  return ret;
}
