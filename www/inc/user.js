function user(username, _tags) {
  // save - call to save tags to database
  this.save=function() {
    ajax("user_savedata", this.tags.data(), this.save_callback.bind(this));
  }

  // save_call_back - after saving
  this.save_callback=function(response) {
    // parse XML response
    var data=response.responseXML;
    if(!data) {
      alert("Error parsing save result:<br>\n"+response.responseText);
      return;
    }
  }

  // constructor
  this.username=username;
  this.tags=new tags(_tags);
}

function login() {
  this.auth_callback=function(response) {
    // parse XML response
    var data=response.responseXML;
    if(!data) {
      alert("Error parsing login result:<br>\n"+response.responseText);
      this.clean_up();
      return;
    }

    // access status information in response
    var status=data.getElementsByTagName("status");
    if(!status.length) {
      alert("Can't find status in response!");
      this.clean_up();
      return;
    }
    status=status[0];

    // if auth_check is false, username/password is wrong
    var auth_check=status.getAttribute("auth_check");
    if(auth_check=="false") {
      alert(t("user:no_auth"));
      this.clean_up();
      return;
    }

    // made it!

    // read auth_id from data
    this.auth_id=status.getAttribute("auth_id");
    cookie_write("auth_id", this.auth_id);

    // clean up, reload
    this.clean_up();
    location.href=get_permalink();
    location.reload();
  }

  this.auth=function() {
    var password=hex_sha1(this.login_password.value);
    ajax("login", { username: this.login_username.value, md5_password: password }, this.auth_callback.bind(this));
  }

  this.create_user=function() {
    if(!this.create_username.value) {
      alert(t("user:no_username"));
      return;
    }

    if(this.create_password.value!=this.create_verify.value) {
      alert(t("user:password_no_match"));
      return;
    }

    var _tags=new tags();
    _tags.set("full_name", this.create_fullname.value);
    _tags.set("email", this.create_email.value);

    var password=hex_sha1(this.create_password.value);
    ajax("user_create", {
       username: this.create_username.value,
       md5_password: password,
       tags: _tags.data()
     },
     this.create_user_callback.bind(this));
  }

  this.create_user_callback=function(response) {
    // parse XML response
    var data=response.responseXML;
    if(!data) {
      alert("Error parsing login result:<br>\n"+response.responseText);
      this.clean_up();
      return;
    }

    // access status information in response
    var status=data.getElementsByTagName("status");
    if(!status.length) {
      alert("Can't find status in response!");
      this.clean_up();
      return;
    }
    status=status[0];

    if(status.getAttribute("created")!="true") {
      var error=status.getAttribute("error");

      alert(t("user:"+error));
      return;
    }
    else {
      // read auth_id from data
      this.auth_id=status.getAttribute("auth_id");
      cookie_write("auth_id", this.auth_id);

      // clean up, reload
      this.clean_up();
      location.href=get_permalink();
      location.reload();
    }
  }

  this.clean_up=function() {
    user_win.close();
  }

  var user_win=new win("options_win");

  // log in
  var head=document.createElement("h3");
  user_win.content.appendChild(head);

  var txt=document.createTextNode(t("user:login_text"));
  head.appendChild(txt);

  var table=document.createElement("table");
  user_win.content.appendChild(table);

  // row 1
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:username"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.login_username=document.createElement("input");
  td.appendChild(this.login_username);
  this.login_username.focus();

  // row 2
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:password"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.login_password=document.createElement("input");
  this.login_password.type="password";
  td.appendChild(this.login_password);

  // row 3
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  td.colspan=2;
  tr.appendChild(td);

  var input=document.createElement("input");
  input.type="button";
  input.value=t("ok");
  input.onclick=this.auth.bind(this);
  td.appendChild(input);

  var input=document.createElement("input");
  input.type="button";
  input.value=t("cancel");
  input.onclick=this.clean_up.bind(this);
  td.appendChild(input);

  // new user
  var head=document.createElement("h3");
  user_win.content.appendChild(head);

  var txt=document.createTextNode(t("user:create_user"));
  head.appendChild(txt);

  var table=document.createElement("table");
  user_win.content.appendChild(table);

  // row 1
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:username"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.create_username=document.createElement("input");
  this.create_username.name="username";
  td.appendChild(this.create_username);

  // row 2
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:full_name"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.create_fullname=document.createElement("input");
  this.create_fullname.name="full_name";
  td.appendChild(this.create_fullname);

  // row 2
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:email"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.create_email=document.createElement("input");
  this.create_email.name="email";
  td.appendChild(this.create_email);

  // row 2
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:password"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.create_password=document.createElement("input");
  this.create_password.type="password";
  this.create_password.name="password";
  td.appendChild(this.create_password);

  // row 2
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:password_verify"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.create_verify=document.createElement("input");
  this.create_verify.type="password";
  this.create_verify.name="password_verify";
  td.appendChild(this.create_verify);

  // row 3
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  td.colspan=2;
  tr.appendChild(td);

  var input=document.createElement("input");
  input.type="button";
  input.value=t("ok");
  input.onclick=this.create_user.bind(this);
  td.appendChild(input);

  var input=document.createElement("input");
  input.type="button";
  input.value=t("cancel");
  input.onclick=this.clean_up.bind(this);
  td.appendChild(input);
}

function logout() {
  cookie_delete("auth_id");
  location.href=get_permalink();
  location.reload();
}
