<?
/**
 * Hooks - Functions can register to hooks and will be called on certain 
 * events in the system.
 *
 * <code>
 * function example($p) {
 *   print "example $p\n";
 * }
 * register_hook("test_hook", "example");
 * </code>
 */

/**
 * Holds a list of all functions which registered a hook
 * @var array array('hook'=>array(fun, fun, fun))
 */
$hooks_intern=array();

/**
 * Call hooks - All registered functions will be called
 * @param text hook The hooks to be called
 * @param any vars A variable which will be passed by reference and can therefore by modified
 * @param any params Additional vars
 */
function call_hooks($hook, $vars=0, $param1=0, $param2=0, $param3=0, $param4=0) {
  global $hooks_intern;

  if(!array_key_exists($hook, $hooks_intern))
    return;

  if($hooks_intern[$hook])
    foreach($hooks_intern[$hook] as $h) {
      $h(&$vars, $param1, $param2, $param3, $param4);
    }
}

/**
 * Register a function to a hook
 * @param text hook The hook the function to register to
 * @param text fun The reference to the function
 */
function register_hook($hook, $fun) {
  global $hooks_intern;

  $hooks_intern[$hook][]=$fun;
}
