new ajax_json(funcname, param_get, [[param_post], callback])

* funcname: function name on the server to be called (function name will be
  prefixed by 'ajax_' -> func 'foo' called 'ajax_foo')
* param_get: get parameters, e.g. { id: 'foo', foo: 'bar' }, will be URL
  encoded to get parameters, e.g. ?fun=foo&param[id]=foo&param[foo]=bar
* param_post: post parameters, will be JSON encoded (optional)
* callback: function which will be called when request finishes. function will
  be passed the result data (json decoded). if callback is omitted a syncronous
  request will be made and the result will be available as property 'result'.
