<?
// info - shows details of an object (ob) in the sidebar
// * Parameter:
// ob is an instance of class geo_object
//
// * Functions:
// show() - Show information about the object in the sidebar
// hide() - Hide the information
//
// * Hooks:
// info calls the following hooks:
// "info" (chapters, object)
//   please add one or more chapter(s) to the chapters array like this:
//   example: array( "head"=>'tags', "weight"=>5, "content"=>content }
//    .head: if there are several chapters with the same head, they will be
//      concatenated to one at the position of the highest weight.
//    .weight: The higher the weight, the lower the position of the content
//    .content: A string with the text, which will be added to a new div 
//      (or a DOMNode, which will be append the the chapter - recommended, but
//      not implemented yet)
//   object is the reference to the geo object
// To show info about an object, the hook "info" will be called
// 
// example: array("head"=>"actions", "weight"=>5, "content"=>"Some Action")
function ajax_info($param, $xml) {
  $ob=load_object($param);

  if(!$ob)
    return 0;

  $chapters=array();
  call_hooks("info", &$chapters, $ob);

  return $chapters;
}
