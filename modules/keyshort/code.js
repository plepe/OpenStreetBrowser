var keyshort_list={};

function keyshort_call(id) {
  if(!id)
    return;
  if(!keyshort_list[id])
    return;

  return keyshort_list[id].callback(id);
}

function register_keyshort(id, callback, default_keyshort, name, keydir) {
  var options={};

  if(typeof id=="object")
    options=id;
  else
    options={
      id: id,
      callback: callback,
      default_keyshort: default_keyshort,
      name: name,
      keydir: keydir
    };

  if(!options.keydir)
    options.keydir="keydown";
  if(!options.name)
    options.name=options.id;

  keyshort_list[options.id]=options;

  $(document).bind(options.keydir+"."+options.default_keyshort,
    keyshort_call.bind(this, options.id));
}

function keyshort_options_show(list) {
  var ret="";

  ret+="<h4>"+lang("keyshort")+"</h4>\n";
  ret+="<div class='options_help'>"+lang("keyshort:help")+"</div>\n";
  ret+="<table>\n";
  for(var k in keyshort_list) {
    ret+="  <tr>\n";
    ret+="    <td>"+keyshort_list[k].default_keyshort+"</td>\n";
    ret+="    <td>"+keyshort_list[k].name+"</td>\n";
    ret+="  </tr>\n";
  }
  ret+="</table>\n";

  list.push([ 2, ret ]);
}

register_hook("options_show", keyshort_options_show);
//register_hook("options_save", keyshort_options_save);
