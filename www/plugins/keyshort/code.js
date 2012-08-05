var keyshort_list={};

function keyshort_call(id, callback) {
  keyshort_list[id][0](id);
}

function register_keyshort(id, callback, default_keyshort, keydir) {
  if(!keydir)
    keydir="keydown";

  keyshort_list[id]=[
    callback,
    keydir+"."+default_keyshort
  ];

  $(document).bind(keydir+"."+default_keyshort, keyshort_call.bind(this, id));
}
