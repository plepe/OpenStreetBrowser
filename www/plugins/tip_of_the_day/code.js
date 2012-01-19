var tip_of_the_day_list=null;

function tip_of_the_day_init() {
  var i=0;

  tip_of_the_day_list=[];

  for (i in lang_str) {
    if(i.match(/^tip_of_the_day:(.*)/)) {
      if((i!="tip_of_the_day:name")&&(i!="tip_of_the_day:next"))
	tip_of_the_day_list.push(i);
    }
  }
}

function tip_of_the_day_dom() {
  if(!tip_of_the_day_list)
    tip_of_the_day_init();

  var i=Math.floor(Math.random()*tip_of_the_day_list.length);

  return creole(lang(tip_of_the_day_list[i]));
}

function tip_of_the_day_next() {
  var div=document.getElementById("tip_of_the_day");

  dom_clean(div);
  div.appendChild(tip_of_the_day_dom());
}
