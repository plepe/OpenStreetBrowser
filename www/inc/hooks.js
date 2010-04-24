var intern_hooks=new Array();
var object_hooks={};

// call_hooks
// Calls a functions registered to this hook
//  why  ... if of hook, e.g. "save"
//  vars ... Vars that can be changed by hook
//  param1..4 ... More parameters
function call_hooks(why, vars, param1, param2, param3, param4) {
  if(intern_hooks[why])
    for(var i=0; i<intern_hooks[why].length; i++) {
      var fun=intern_hooks[why][i];
      fun(vars, param1, param2, param3, param4);
    }
}

// register_hook
// Registers a function to be called when hook "why" is fired
//   why ... id of hook, e.g. "save"
//   fun ... the function to be called
//   ob  ... saves this hook to be part of object ob, therefore hook can 
//           be saveely removed, when object is being discarded
function register_hook(why, fun, ob) {
  if(!intern_hooks[why])
    intern_hooks[why]=new Array();

  intern_hooks[why].push(fun);

  if(ob) {
    if(!object_hooks[ob])
      object_hooks[ob]=[];
    object_hooks[ob].push([ why, fun ]);
  }
}

// unregister_object_hooks
// Unregisters all hooks of an object
//  ob ... This object
function unregister_object_hooks(ob) {
  if(!object_hooks[ob])
    return;

  for(var i=0; i<object_hooks[ob].length; i++) {
    var why=object_hooks[ob][i][0];
    var fun1=object_hooks[ob][i][1];

    for(var j=0; j<intern_hooks[why].length; j++) {
      var fun2=intern_hooks[why][j];
      if(fun1==fun2) {
	intern_hooks[why].splice(j, 1);
	j--;
      }
    }
  }

  delete(object_hooks[ob]);
}
