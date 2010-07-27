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

    var ret=ajax_call("git_obj_save", param);

    if(this.save_done)
      this.save_done(file, ret);

    return ret;
  }

  this.dir=dir;
  this.id=id;
  this.files=files;

  this.ajax_param=new clone(this.dir.ajax_param);
  this.ajax_param.obj=this.id;
}


