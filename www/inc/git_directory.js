function git_file(dir, xml) {
  this.url=function(file, version) {
    var param={};
    param.directory=this.directory.path;
    param.git_file=this.id;
    param.file=file;
    if(version)
      param.version=version;

    ajax_build_request(param, null, p);
    return "git_download.php?"+p.join("&");
  }

  this.directory=dir;
  this.id=xml.getAttribute("id");

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
  if(!file_proto)
    file_proto=git_file;
  ajax("git_directory_load", { path: this.path }, this.load_callback.bind(this));
}
