var tip_of_the_day_count=-1;

function tip_of_the_day_init() {
  var i=0;

  while(lang_str["tip_of_the_day:"+i]) {
    i++;
  }

  tip_of_the_day_count=i;
}

function tip_of_the_day_dom() {
  if(tip_of_the_day_count==-1)
    tip_of_the_day_init();

  var i=Math.floor(Math.random()*tip_of_the_day_count);

  return creole(lang("tip_of_the_day:"+i));
}
