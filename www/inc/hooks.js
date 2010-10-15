/**
 * Hooks - Functions can register to hooks and will be called on certain 
 * events in the system.
 *
 * <code>
 * function example(p) {
 *   alert("example "+p);
 * }
 * register_hook("test_hook", example);
 * </code>

 */

/**
 * Holds a list of all functions which registered a hook
 * @var array array('hook'=>array(fun, fun, fun))
 */
var hooks_intern=new Array();

/**
 * Holds a list of all hooks of an object, to savely remove all hooks of an object
 */
var hooks_object={};

/**
 * Call hooks - All registered functions will be called
 * @param text hook The hooks to be called
 * @param any vars A variable which will be passed by reference and can therefore by modified
 * @param any params Additional vars
 */
function call_hooks(hook, vars, param1, param2, param3, param4) {
  if(hooks_intern[hook])
    for(var i=0; i<hooks_intern[hook].length; i++) {
      hooks_intern[hook][i](vars, param1, param2, param3, param4);
    }
}

/**
 * Register a function to a hook
 * @param text hook The hook the function to register to
 * @param text fun The reference to the function
 * @param object saves this hook to be part of object ob, therefore hook can be saveely removed, when object is being discarded
 */
function register_hook(hook, fun, ob) {
  if(!hooks_intern[hook])
    hooks_intern[hook]=new Array();

  hooks_intern[hook].push(fun);

  if(ob) {
    if(!hooks_object[ob])
      hooks_object[ob]=[];
    hooks_object[ob].push([ hook, fun ]);
  }
}

/**
 * Unregisters all hooks of an object
 * @param object The object
 */
function unregister_hooks_object(ob) {
  if(!hooks_object[ob])
    return;

  for(var i=0; i<hooks_object[ob].length; i++) {
    var hook=hooks_object[ob][i][0];
    var fun1=hooks_object[ob][i][1];

    for(var j=0; j<intern_hooks[hook].length; j++) {
      var fun2=intern_hooks[hook][j];
      if(fun1==fun2) {
	intern_hooks[hook].splice(j, 1);
	j--;
      }
    }
  }

  delete(hooks_object[ob]);
}
