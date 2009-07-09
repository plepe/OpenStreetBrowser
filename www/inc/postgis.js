function postgis(text) {
  this.ob=null;

  this.parse=function(way) {
    var p=way.indexOf("(");
    this.type=way.substr(0, p);
    //var m=way.match("([A-Z]*)\\(([0-9\. ,]*)\\)");
    this.data=way.substr(p+1, way.length-p-2)
    this.way=null;
  }

  this.parse_pos_list=function(x) {
    var ret=[];
    var pos_list=x.split(",");
    for(var i=0; i<pos_list.length; i++) {
      ret.push(pos_list[i].split(" "));
    }

    return ret;
  }

  this.geo=function() {
    if(this.ob)
      return this.ob;

    var p, p1, p2;
    this.ob=[];

    switch(this.type) {
      case "POINT":
	var w=this.parse_pos_list(this.data);
	this.ob.push(new OpenLayers.Geometry.Point(w[0][0], w[0][1]))
	break;
      case "LINESTRING":
	var w=this.parse_pos_list(this.data);
	p1=[];
	for(var j=0; j<w.length; j++) {
	  p1.push(new OpenLayers.Geometry.Point(w[j][0], w[j][1]));
	}
	this.ob.push(new OpenLayers.Geometry.LineString(p1));
	break;
      case "MULTILINESTRING":
      case "POLYGON":
	p2=[];
	var w=this.data.substr(1, this.data.length-2).split("),(");
	for(var i=0; i<w.length; i++) {
          var w1=w[i];
	  w1=this.parse_pos_list(w1);
	  p1=[];
	  for(var j=0; j<w1.length; j++) {
	    p1.push(new OpenLayers.Geometry.Point(w1[j][0], w1[j][1]));
	  }
	  this.ob.push(new OpenLayers.Geometry.LineString(p1));
	}
	break;
      case "GEOMETRYCOLLECTION":
        var b=0;
	var str=this.data;
	var pos=0;
	var start=0;

	do {
	  var open=str.indexOf("(", pos);
	  var close=str.indexOf(")", pos);
	  if(open==-1)
	    open=str.length;
	  if(open<close) {
	    b++;
	    pos=open+1;
	  }
	  else {
	    b--;
	    pos=close+1;
	    if(b==0) {
	      var part=str.substr(start, pos-start);
	      var part_ob=new postgis(part);
	      var part_geo=part_ob.geo();
	      for(var i=0; i<part_geo.length; i++) {
		this.ob.push(part_geo[i]);
	      }
	      pos++;
	      start=pos;
	    }
	  }
	}
	while(pos<str.length);
	break;
      default:
	alert("unknown geo type "+this.type+"!\n");
    }

    return this.ob;
  }

  this.parse(text);
}
