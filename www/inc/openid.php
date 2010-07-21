<?
function openid_user_is_valid($auth, $username) {
  global $openid_response;

  if($openid_response&&($openid_response->status == Auth_OpenID_SUCCESS)) {
    $auth=1;
    return;
  }
}

function openid_login_form($text) {
  global $lang_str;

  $text.=
    "<hr>\n".
    "<form action='' method='post'>\n".
    "<table>\n".
    "<tr><td>$lang_str[openid_req]:</td></tr>\n".
    "<tr><td><input type='text' name='openid_id' size='30' /></td></tr>\n".
    "<tr><td><input type='submit' name='submit' value='$lang_str[tool_userauth_ok]' /></td></tr>\n".
    "</table>\n".
    "</form>\n";
}

function openid_authenticate() {
  global $openid_response;
  global $db;

  if($_REQUEST[openid_id]) {
    require_once "Auth/OpenID/Consumer.php";
    require_once "Auth/OpenID/FileStore.php";

    // create file storage area for OpenID data
    $store = new Auth_OpenID_FileStore('./oid_store');

    // create OpenID consumer
    $consumer = new Auth_OpenID_Consumer($store);

    // begin sign-in process
    // create an authentication request to the OpenID provider
    $auth = $consumer->begin($_REQUEST['openid_id']);
    if (!$auth) {
      die("ERROR: Please enter a valid OpenID.");
    }

    // redirect to OpenID provider for authentication
    $_SESSION[openid_saveurl]=$_SERVER[REQUEST_URI];
    $url = $auth->redirectURL("http://$_SERVER[HTTP_HOST]$web_path", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    header('Location: ' . $url);
  }
  elseif($_REQUEST[openid_identity]) {
    // include files
    require_once "Auth/OpenID/Consumer.php";
    require_once "Auth/OpenID/FileStore.php";

    // create file storage area for OpenID data
    $store = new Auth_OpenID_FileStore('./oid_store');

    // create OpenID consumer
    // read response from OpenID provider
    $consumer = new Auth_OpenID_Consumer($store);
    $openid_response = $consumer->complete("http://$_SERVER[HTTP_HOST]$_SESSION[openid_saveurl]");

    // set session variable depending on authentication result
    if ($openid_response->status == Auth_OpenID_SUCCESS) {
      $_SESSION[current_user]=get_user($_REQUEST[openid_identity]);
      session_register("current_user");
      $db->query("insert or replace into openid_user values ( '$_REQUEST[openid_identity]' )");
    } else {
      print "OpenID-Authentication failed";
      print_r($openid_response);
    }

    header("Location: http://$_SERVER[HTTP_HOST]$_SESSION[openid_saveurl]");
  }
}

function openid_userlist($user_list) {
  global $db;

  $res=$db->query("select * from openid_user");
  while($elem=$res->fetch()) {
    $user_list[$elem[username]]=0;
  }
}

extension_update_db("openid", 1, "create table openid_user ( username varchar(64) not null primary key )");
register_hook("user_is_valid", openid_user_is_valid);
register_hook("small_login_form", openid_login_form);
register_hook("login_form", openid_login_form);
register_hook("authenticate", openid_authenticate);
register_hook("user_list", openid_userlist);
