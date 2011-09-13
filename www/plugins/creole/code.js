var creole_parser;

function creole(txt) {
  if(!creole_parser)
    creole_parser=new Parse.Simple.Creole();

  var div=document.createElement("div");

  creole_parser.parse(div, txt);

  return div;
}
