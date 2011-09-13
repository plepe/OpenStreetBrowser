var creole_parser;
var creole_interwiki_links={
  "osm": "http://wiki.openstreetmap.org/wiki/%s"
};

// Processes interwiki-links and such
function creole_preprocess(div) {
  var m;

  var as=div.getElementsByTagName("a");
  for(var i=0; i<as.length; i++) {
    var a=as[i];
    if(m=a.href.match(/^([^:]*):(.*)$/)) {
      if(creole_interwiki_links[m[1]]) {
	a.href=sprintf(creole_interwiki_links[m[1]], m[2]);
	a.target="_new";
      }
    }
  }
}

function creole(txt) {
  if(!creole_parser)
    creole_parser=new Parse.Simple.Creole();

  var div=document.createElement("div");

  creole_parser.parse(div, txt);
  creole_preprocess(div);

  return div;
}
