function git_file(dir, xml) {
  this.url=function(file, version) {
    var param=this.ajax_param;
    param.file=file;
    if(version)
      param.version=version;

    var p=[];
    ajax_build_request(param, null, p);

    return "git_download.php?"+p.join("&");
  }

  this.upload_form=function(file) {
    var iframe=document.createElement("iframe");

    var param=this.ajax_param;
    param.file=file;
    param.commit_data=this.directory.commit_data;

    var p=[];
    ajax_build_request(param, null, p);

    iframe.src="git_upload.php?"+p.join("&");
    return iframe;
  }

  this.save=function(file, content) {
    var param=this.ajax_param;
    param.file=file;
    param.content=content;
    param.commit_data=this.directory.commit_data;

    var ret=ajax_call("git_file_save", param);
  }

  this.directory=dir;
  this.id=xml.getAttribute("id");
  this.ajax_param={};
  this.ajax_param.path=this.directory.path;
  this.ajax_param.git_file=this.id;

  var files=xml.getElementsByTagName("file");
  this.files=[];
  for(var i=0; i<files.length; i++) {
    this.files.push(files[i].firstChild.nodeValue);
  }
}

function git_directory(path, callback, file_proto) {
  // file_list
  this.file_list=function() {
    return this.files;
  }

  // commit_start
  this.commit_start=function(param) {
    var p=array_merge(this.ajax_param, param);

    var ret=ajax_call("git_commit_start", p);
    this.commit_data=ret;
    return ret;
  }

  // commit_start
  this.commit_end=function(message) {
    var p=this.ajax_param;
    p.commit_data=this.commit_data;
    p.message=message;

    var ret=ajax_call("git_commit_end", p);
    return ret;
  }

  // create_file
  this.create_file=function(id) {
    var p=this.ajax_param;
    if(id)
      p.id=id;
    p.commit_data=this.commit_data;

    var ret=ajax_call("git_create_file", p);
    var result=ret.responseXML.getElementsByTagName("result");
    result=result[0];

    return new file_proto(this, result);
  }

  // commit_start
  this.commit_start=function(param) {
    var p=array_merge(this.ajax_param, param);

    var ret=ajax_call("git_commit_start", p);
    this.commit_data=ret;
    return ret;
  }

  // load_callback
  this.load_callback=function(response) {
    // parse XML response
    var data=response.responseXML;
    if(!data) {
      alert("Error parsing save result:<br>\n"+response.responseText);
      return;
    }

    var files=data.getElementsByTagName("git_file");
    this.files=[];

    for(var i=0; i<files.length; i++) {
      this.files.push(new file_proto(this, files[i]));
    }

    this.loaded=true;

    if(callback)
      callback();
  }

  // constructor
  this.path=path;
  this.loaded=false;
  this.ajax_param={ path: this.path };
  if(!file_proto)
    file_proto=git_file;
  ajax("git_directory_load", this.ajax_param, this.load_callback.bind(this));
}
