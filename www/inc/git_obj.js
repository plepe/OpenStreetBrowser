function git_obj(dir, id, files) {
  this.url=function(file, version) {
    var param=new clone(this.ajax_param);
    param.file=file;
    if(version)
      param.version=version;

    var p=[];
    ajax_build_request(param, null, p);

    return "git_download.php?"+p.join("&");
  }

  this.commit_id=function() {
    return this.dir.commit_id();
  }

  this.upload_form=function(file) {
    var iframe=document.createElement("iframe");

    var param=new clone(this.ajax_param);
    param.file=file;
    param.commit_id=this.commit_id();

    if(!param.commit_id) {
      alert("No commit started");
      return null;
    }

    var p=[];
    ajax_build_request(param, null, p);

    iframe.src="git_upload.php?"+p.join("&");
    return iframe;
  }

  this.save=function(file, content) {
    var param=new clone(this.ajax_param);
    param.file=file;
    param.content=content;
    param.commit_id=this.commit_id();

    if(!param.commit_id) {
      alert("No commit started");
      return null;
    }

    var response=ajax("git_obj_save", param);
    var ret=response.return_value;

    if(this.save_done)
      this.save_done(file, ret);

    return ret;
  }

  this.load=function(file, version_branch) {
    var param=new clone(this.ajax_param);
    param.file=file;
    param.version_branch=version_branch;

    var response=ajax("git_obj_load", param);
    var ret=response.return_value;

    return ret;
  }

  this.dir=dir;
  this.id=id;
  this.files=files;

  this.ajax_param=new clone(this.dir.ajax_param);
  this.ajax_param.obj=this.id;
}


