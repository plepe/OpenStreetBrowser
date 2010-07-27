function git_dir(master, id, obj_proto) {
  // file_list
  this.obj_list=function() {
    var p=new clone(this.ajax_param);

    var list=ajax_call("git_obj_list", p);

    var ret=[];
    for(var id in list) {
      ret.push(new obj_proto(this, id, list[id]));
    }

    return ret;
  }

  this.commit_id=function() {
    return this.master.commit_id();
  }

  // create_obj
  this.create_obj=function(id) {
    var p=new clone(this.ajax_param);
    if(id)
      p.obj=id;
    p.commit_id=this.commit_id();

    var ret=ajax_call("git_create_obj", p);
    var result=ret.responseXML.getElementsByTagName("result");
    result=result[0];

    return new obj_proto(this, result.getAttribute("id"), []);
  }

  // get obj
  this.get_obj=function(id) {
    var p=new clone(this.ajax_param);
    p.obj=id;

    var ret=ajax_call("git_get_obj", p);

    if(!ret)
      return;

    return new obj_proto(this, id, ret);
  }

  // constructor
  this.master=master;
  this.id=id;
  if(!obj_proto)
    obj_proto=git_obj;

  this.ajax_param=new clone(this.master.ajax_param);
  this.ajax_param.dir=this.id;
}
