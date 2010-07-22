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
    var password=hex_sha1(this.input_password.value);
    ajax("login", { username: this.input_username.value, md5_password: password }, this.auth_callback.bind(this));
  }

  this.clean_up=function() {
    user_win.close();
  }

  var user_win=new win("options_win");

  var form=document.createElement("form");
  form.onsubmit=this.auth.bind(this);
  user_win.content.appendChild(form);

  var table=document.createElement("table");
  form.appendChild(table);

  // row 1
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:username"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.input_username=document.createElement("input");
  td.appendChild(this.input_username);

  // row 2
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  tr.appendChild(td);

  var txt=document.createTextNode(t("user:password"));
  td.appendChild(txt);

  var td=document.createElement("td");
  tr.appendChild(td);

  this.input_password=document.createElement("input");
  this.input_password.type="password";
  td.appendChild(this.input_password);

  // row 3
  var tr=document.createElement("tr");
  table.appendChild(tr);

  var td=document.createElement("td");
  td.colspan=2;
  tr.appendChild(td);

  var input=document.createElement("input");
  input.type="submit";
  input.value="Ok";
  td.appendChild(input);
}
