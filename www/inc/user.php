<?
/* user.php
 * - User Administration
 *
 * Copyright (c) 2010 Stephan Plepelits <skunk@xover.mud.at>
 * as part of OpenStreetBrowser - http://gitorious.org/openstreetbrowser
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

// ajax_login - called from webapp when authenticating user
function ajax_login($param, $xml) {
  // create top level xml object "result"
  $ret=$xml->createElement("result");
  $xml->appendChild($ret);

  // create xml object "status"
  $status=$xml->createElement("status");
  $ret->appendChild($status);

  // create user object, authenticate
  $user=new user($param);

  // according to authentication level, set attributes
  if(!$user->authenticated) {
    $status->setAttribute("auth_check", "false");
  }
  else {
    $status->setAttribute("auth_check", "true");
    $status->setAttribute("auth_id", $user->auth_id);
  }
}

class User {
  var $username;
  var $authenticated=false;
  var $auth_id=null;
  var $pass;

  function __construct($param=0) {
    $this->authenticated=false;

    if(!$param) {
      return;
    }

    // Other methods for auth, e.g. OpenID
    $other_auth=null;
    call_hooks("user_is_valid", &$other_auth, $param);
    if($other_auth) {
      $this->username=$other_auth['username'];
      $this->pg_username=postgre_escape($param['username']);
    }
    else {
      $this->username=$param['username'];
      $this->pg_username=postgre_escape($param['username']);

      $res=sql_query("select * from user_list where username={$this->pg_username}");
      if(!($elem=pg_fetch_assoc($res))) {
	return;
      }

      if($elem['md5_password']!=$param['md5_password']) {
	return;
      }
    }

    $this->authenticated=true;
    $this->create_auth();
  }

  function create_auth() {
    $this->auth_id=uniqid();
    sql_query("insert into auth values ('{$this->auth_id}', {$this->pg_username}, now())");
  }

  function valid_user() {
    return ($this->auth);
  }
  
  function authenticate($pass) {
    if($this->username=="anonymous")
      return 1;

    if(crypt($pass, $this->pass)==$this->pass) {
      $this->auth=1;
      return 1;
    }
    return 0;
  }

  function toolbox() {
    global $lang_str;

    print "<div class='userinfo' onClick='authenticate_user(this)'>\n";
    if($this->username=="anonymous")
      print "$lang_str[tool_userauth_login]";
    else
      print "$lang_str[tool_userauth_loggedin] $this->username";
    print "</div>\n";
  }
};

$user_list=0;

function user_list() {
  global $passwd_file;
  global $user_list;

  if($user_list)
    return $user_list;
  else
    $user_list=array();

  $f=fopen($passwd_file, "r");
  while($r=fgets($f)) {
    $r=chop($r);
    if(($r!="")&&(substr($r, 0, 1)!="#")) {
      $r=explode(":", $r);

      $user_list[$r[0]]=0;
    }
  }

  call_hooks("user_list", &$user_list);

  return $user_list;
}

function get_user($username) {
  global $user_list;

  user_list();

  if(!$user_list[$username])
    $user_list[$username]=new User($username);

  return $user_list[$username];
}

function small_login_form() {
  global $lang_str;
  global $url_page;
  global $page;

  $login_form=
    "<form action='' method='post' id='small_login_form'>\n".
    "<table>\n".
    "<tr><td>$lang_str[tool_userauth_username]:</td><td><input name='username' id='small_login_username'></td></tr>\n".
    "<tr><td>$lang_str[tool_userauth_password]:</td><td><input name='password' id='small_login_password' type='password'></td></tr>\n".
    "<tr><td><input type='submit' id='user_login_submit' value='$lang_str[tool_userauth_ok]'></td><td align='right'><input type='submit' id='user_login_logout' value='$lang_str[tool_userauth_logout]'></td></tr>\n".
    "</table>\n".
    "</form>\n";

  call_hooks("small_login_form", &$login_form);
  $login_form="<span class='small_login_form' id='small_login'>$login_form</span>";

  print $login_form;
}

function login_form() {
global $lang_str;
global $page;

  $login_form=
    "<form action='' method='post' id='login_form'>\n".
    "<table>\n".
    "<tr><td>$lang_str[tool_userauth_username]:</td><td><input name='username' id='small_login_username'></td></tr>\n".
    "<tr><td>$lang_str[tool_userauth_password]:</td><td><input name='password' id='small_login_password' type='password'></td></tr>\n".
    "<tr><td><input type='submit' id='user_login_submit' value='$lang_str[tool_userauth_ok]'></td><td align='right'><input type='submit' id='user_login_logout' value='$lang_str[tool_userauth_logout]'></td></tr>\n".
    "</table>\n".
    "</form>\n";

  call_hooks("login_form", &$login_form);
  $login_form="$lang_str[tool_userauth_norights]\n".
    "<div class='login_form' id='login'>$login_form</div>";

  print $login_form;
}

function new_user() {
}
