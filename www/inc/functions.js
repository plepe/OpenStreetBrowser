function keys(ob) {
  var ret=[];

  for(var i in ob) {
    ret.push(i);
  }

  return ret;
}

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

// array_delete removes an element from the array at the pos position
// example:
//   array_remove([ 1, 2, 3, 4, 5 ], 2)
//   -> [ 1, 2, 4, 5 ]
function array_remove(arr, pos) {
  var part1=arr.concat([]);
  var part2=part1.splice(pos+1);
  return part1.splice(0, pos).concat(part2);
}
