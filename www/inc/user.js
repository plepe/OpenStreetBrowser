function login() {
  this.auth_callback=function(data) {
    // read auth_id from data
    this.auth_id="1234";

    cookie_write("auth", this.auth_id);

    location.href=get_permalink();
    location.reload();
  }

  this.auth=function() {
    var password=hex_sha1(this.input_password.value);
    //ajax("login", { username: this.input_username.value, password: password }, this.auth_callback.bind(this));
    this.auth_callback();
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
