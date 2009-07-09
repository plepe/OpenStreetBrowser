var intern_hooks=new Array();

function call_hooks(why, vars, param1, param2, param3, param4) {
  if(intern_hooks[why])
    for(var i=0; i<intern_hooks[why].length; i++) {
      intern_hooks[why][i](vars, param1, param2, param3, param4);
    }
}

function register_hook(why, fun) {
  if(!intern_hooks[why])
    intern_hooks[why]=new Array();

  intern_hooks[why].push(fun);
}
