<?
global $tip_of_the_day_count;

function tip_of_the_day_init() {
  global $tip_of_the_day_count;
  global $lang_str;

  $i=0;

  while($lang_str["tip_of_the_day:$i"]) {
    $i++;
  }

  $tip_of_the_day_count=$i;
}

function tip_of_the_day_html() {
  global $tip_of_the_day_count;

  if(!isset($tip_of_the_day_count))
    tip_of_the_day_init();

  $i=floor(rand(0, $tip_of_the_day_count-1));

  return creole_html(lang("tip_of_the_day:$i"));
}
