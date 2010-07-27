function git_master() {
  var commit_id=null;

  // commit_start
  this.commit_start=function(param) {
    var p=array_merge(new clone(this.ajax_param), param);

    var ret=ajax_call("git_commit_start", p);
    if(typeof ret=="string")
      commit_id=ret;
    else
      alert("git_commit_start does not return string!");
    return ret;
  }

  // commit_id
  this.commit_id=function() {
    return commit_id;
  }

  // commit_end
  this.commit_end=function(message) {
    var p=new clone(this.ajax_param);
    p.commit_id=this.commit_id();
    p.message=message;

    var ret=ajax_call("git_commit_end", p);
    commit_id=null;
    return ret;
  }

  // commit_cancel
  this.commit_cancel=function(param) {
    var p=new clone(this.ajax_param);
    p.commit_id=this.commit_id();

    var ret=ajax_call("git_commit_cancel", p);
    commit_id=null;
    return ret;
  }

  // constructor
  this.loaded=false;
  this.ajax_param={ };
}
