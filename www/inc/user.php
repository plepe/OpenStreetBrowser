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
};

function user_list() {
  $user_list=array();

  $res=sql_query("select * from user_list");
  while($elem=pg_fetch_assoc($res)) {
    $user_list[]=$elem['username'];
  }

  call_hooks("user_list", &$user_list);

  return $user_list;
}
