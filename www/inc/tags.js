function tags(d) {
  var data;

  if(typeof d=="object") {
    data=d;
  }

  this.get=function(k) {
    return data[k];
  }

  this.get_lang=function(k) {
    var ret;
    
    if(ret=data[k+":"+lang])
      return ret;

    return data[k];
  }

  this.get_available_languages=function(k) {
    var ret=[];

    for(var i in data) {
      if((m=i.match(/^(.*):(.*)$/, k))&&(m[1]==k))
	ret.push(m[2]);
    }

    return ret;
  }

  this.set=function(k, v) {
    data[k]=v;
  }

  this.erase=function(k) {
    delete(data[k]);
  }

  this.data=function() {
    return data;
  }
}
