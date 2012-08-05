var keyshort_list={};

function keyshort_call(id, callback) {
  keyshort_list[id].callback(id);
}

function register_keyshort(id, callback, default_keyshort, keydir) {
  var options={};

  if(typeof id=="object")
    options=id;
  else
    options={
      id: id,
      callback: callback,
      default_keyshort: default_keyshort,
      keydir: keydir
    };

  if(!options.keydir)
    options.keydir="keydown";

  keyshort_list[options.id]=options;

  $(document).bind(options.keydir+"."+options.default_keyshort,
    keyshort_call.bind(this, options.id));
}
