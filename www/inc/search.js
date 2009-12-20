function search_focus(ob) {
  ob.value="";
}

function search(ob) {
  var details_content=document.getElementById("details_content");
  var details=document.getElementById("details");
  location.hash="#search_"+ob.value;
}

function real_search(value) {
  ajax("search", { "value": value }, search_result);
  details_content.innerHTML="Loading ...";
  details.className="info_loading";
}

function search_result(data) {
  var details_content=document.getElementById("details_content");
  var details=document.getElementById("details");

  var text=data.responseXML.getElementsByTagName("result");
  details_content.innerHTML=text[0].textContent;
  details.className="info";

  var osm=data.responseXML.getElementsByTagName("osm");
  load_objects_from_xml(osm);

}
