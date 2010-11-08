<?
function box($listname, $text, $state="opened") {
  $ret ="<div class=\"box_$state\" id=\"list|{$listname}\">\n";
  $ret.="<h1><input type='checkbox' name='$listname' ".
        ($state=="opened"?" checked='checked'":"")." onChange='box_change(this)' />";
  $ret.="<a href='javascript:box_click(\"$listname\")'>".
    lang("list_$listname")."</a></h1>\n";

  $ret.=$text;

  $ret.="</div>\n";

  return $ret;
}

function box_closed($listname) {
  return box($listname, "", "closed");
}

function subbox($listname, $sublistname, $text, $state="opened") {
  $ret ="<div class=\"subbox_$state\" id=\"list|{$listname}|{$sublistname}\">\n";
  $ret.="<h2><input type='checkbox' name='$listname|$sublistname' ".
        ($state=="opened"?" checked='checked'":"")." onChange='box_change(this)' />\n";
  $ret.="<a href='javascript:box_click(\"$listname\", \"$sublistname\")'>".
    lang("list_$sublistname")."</a></h2><div class=\"subbox_content\">\n";

  $ret.=$text;

  $ret.="</div></div>\n";

  return $ret;
}

function subbox_closed($listname, $sublistname) {
  return subbox($listname, $sublistname, "", "closed");
}

function subsubbox($listname, $sublistname, $subsublistname, $text, $title_append="") {
  if($title_append!="")
    $title_append=" ($title_append)";
  $ret.="<h4>".lang("list_$subsublistname").$title_append;
  $ret.="</h4>\n";
  $ret.=$text;
  $ret.="\n";

  return $ret;
}

function infobox($headname, $text, $state="opened") {
  $ret ="<div class=\"infobox_$state\" id=\"head|{$headname}\">\n";
  $ret.="<h2><input type='checkbox' name='$headname' ".
        ($state=="opened"?" checked='checked'":"")." onChange='box_change(this)' />\n";
  $ret.="<a href='javascript:box_click(\"$headname\")'>".
    lang("head:$headname")."</a></h2><div class=\"infobox_content\">\n";

  $ret.=$text;

  $ret.="</div></div>\n";

  return $ret;
}

function infobox_closed($listname) {
  return infobox($listname, "", "closed");
}

function make_list($text) {
  return "<ul>".$text."</ul>";
}

function list_entry($id, $name, $app=array()) {
  if(sizeof($app)>0)
    $app=" (".implode(", ", $app).")";
  else
    $app="";

  return "<li class='listitem' id='list_{$id}'><a href='#{$id}' onMouseOver='set_highlight([\"{$id}\"])' onMouseOut='unset_highlight()'>{$name}</a>$app</li>\n";
}
