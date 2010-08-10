function git_dir(master, id, obj_proto) {
  var obj_cache={};

  // file_list
  this.obj_list=function() {
    var p=new clone(this.ajax_param);

    var response=ajax("git_obj_list", p);
    var list=response.return;

    var ret=[];
    for(var id in list) {
      var obj;

      if(obj_cache[id])
        obj=obj_cache[id];
      else {
	obj=new obj_proto(this, id, list[id])
	obj_cache[id]=obj;
      }

      ret.push(obj);
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

    var ret=ajax("git_create_obj", p);
    var result=ret.responseXML.getElementsByTagName("result");
    result=result[0];

    return new obj_proto(this, result.getAttribute("id"), []);
  }

  // get obj
  this.get_obj=function(id) {
    if(obj_cache[id])
      return obj_cache[id];

    var p=new clone(this.ajax_param);
    p.obj=id;

    var response=ajax("git_get_obj", p);
    var ret=response.return;

    if(!ret)
      return;

    var obj=new obj_proto(this, id, ret);
    obj_cache[id]=obj;
    return obj;
  }

  // constructor
  this.master=master;
  this.id=id;
  if(!obj_proto)
    obj_proto=git_obj;

  this.ajax_param=new clone(this.master.ajax_param);
  this.ajax_param.dir=this.id;
}
