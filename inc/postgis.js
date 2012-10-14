function postgis(text) {
  this.ob=null;
  this.text=text;

  // For some reason OpenLayers doesn't understand MULTIPOINTs ...
  // convert them to GEOMETRYCOLLECTION of POINTs
  var match;
  while(match=this.text.match(/^(.*)MULTIPOINT\(([0-9\., ]+)\)(.*)$/)) {
    this.text=
      match[1]+
      "GEOMETRYCOLLECTION(POINT("+
      match[2].split(",").join("),POINT(")+
      "))"+match[3];
  }

  this.geo=function() {
    if(this.ob)
      return this.ob;

    var parser=new OpenLayers.Format.WKT();
    this.ob=parser.read(this.text);

    if(!this.ob)
      this.ob=[];
    else if(!this.ob.length)
      this.ob=[this.ob];

    this.ob=array_remove_undefined(array_unfold(this.ob));

    return this.ob;
  }
}
