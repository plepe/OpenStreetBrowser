<?
global $tip_of_the_day_list;

function tip_of_the_day_init() {
  global $tip_of_the_day_list;
  global $lang_str;

  $tip_of_the_day_list=array();

  foreach($lang_str as $i=>$text) {
    if(preg_match("/^tip_of_the_day:(.*)$/", $i)) {
      if(($i!="tip_of_the_day:name")&&($i!="tip_of_the_day:next"))
	$tip_of_the_day_list[]=$i;
    }
  }
}

function tip_of_the_day_html() {
  global $tip_of_the_day_list;

  if(!isset($tip_of_the_day_list))
    tip_of_the_day_init();

  $i=floor(rand(0, sizeof($tip_of_the_day_list)-1));

  return creole_html(lang($tip_of_the_day_list[$i]));
}

function tip_of_the_day_right_bar(&$content) {
  $ret="";

  $ret.="<h1>".lang("tip_of_the_day:name")."</h1>\n";
  $ret.="<div id='tip_of_the_day'>".tip_of_the_day_html()."</div>\n";
  $ret.="<a href='javascript:tip_of_the_day_next()'>".lang("tip_of_the_day:next")."</a>\n";

  $content[]=array(0, $ret);
}

register_hook("right_bar_content", "tip_of_the_day_right_bar");
