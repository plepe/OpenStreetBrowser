function postgis(text) {
  this.ob=null;
  this.text=text;

  this.geo=function() {
    if(this.ob)
      return this.ob;

    var parser=new OpenLayers.Format.WKT();
    this.ob=parser.read(this.text);

    if(!this.ob)
      this.ob=[];
    else if(!this.ob.length)
      this.ob=[this.ob];

    return this.ob;
  }
}
