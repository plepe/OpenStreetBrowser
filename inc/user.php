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

function ajax_user_savedata($param, $xml) {
  global $current_user;
  
  user_check_auth();

  $current_user->tags->set_data($param);
  $current_user->save();

  // create top level xml object "result"
  $ret=$xml->createElement("result");
  $xml->appendChild($ret);

  // create xml object "status"
  $status=$xml->createElement("status");
  $ret->appendChild($status);
  $status->setAttribute("saved", "true");
}

function ajax_user_create($param, $xml) {
  global $current_user;
  global $db_central;

  // create top level xml object "result"
  $ret=$xml->createElement("result");
  $xml->appendChild($ret);

  // create xml object "status"
  $status=$xml->createElement("status");
  $ret->appendChild($status);

  $pg_username=postgre_escape($param['username']);

  $res=sql_query("select * from user_list where username=$pg_username", $db_central);
  if($elem=pg_fetch_assoc($res)) {
    $status->setAttribute("created", "false");
    $status->setAttribute("error", "user_exists");
  }
  else {
    $md5_password=postgre_escape($param['md5_password']);
    sql_query("insert into user_list values ($pg_username, $md5_password, ".array_to_hstore($param['tags']).")", $db_central);
    $status->setAttribute("created", "true");

    $user=new user($param, 1);
    $user->create_auth();
    $status->setAttribute("auth_id", $user->auth_id);
  }
}

class User {
  var $username;
  var $authenticated=false;
  var $auth_id=null;
  var $pass;
  var $tags=null;

  function __construct($param=0, $force_auth=0) {
    global $db_central;

    $this->authenticated=false;

    // anonymous user
    if(!$param) {
      $this->load_anonymous();
      return;
    }

    // forced authentication (e.g. we found a valid auth_id)
    if($force_auth) {
      $this->username=$param['username'];
      $this->pg_username=postgre_escape($this->username);
      $this->auth_id=$param['auth_id'];
      $this->authenticated=true;
    }
    else {
      // Other methods for auth, e.g. OpenID
      $other_auth=null;
      call_hooks("user_is_valid", $other_auth, $param);
      if($other_auth) {
	$this->username=$other_auth['username'];
	$this->pg_username=postgre_escape($other_auth['username']);
	$this->authenticated=true;
      }
      // also other auth methods did not work
      else {
	$this->username=$param['username'];
	$this->pg_username=postgre_escape($param['username']);
      }
    }

    // get user from database
    $res=sql_query("select * from user_list where username={$this->pg_username}", $db_central);
    // user does not exist -> anonymous
    if(!($elem=pg_fetch_assoc($res))) {
      $this->load_anonymous();
      return;
    }

    // not authenticated yet, check password
    if(!$this->authenticated) {
      if($elem['md5_password']!=$param['md5_password']) {
	unset($this->username);
	unset($this->pg_username);
	$this->load_anonymous();
	return;
      }
    }

    $this->authenticated=true;
    $this->tags=new tags(parse_hstore($elem['osm_tags']));
    $this->create_auth();
  }

  function create_auth() {
    global $db_central;

    if($this->auth_id)
      return;

    $this->auth_id=uniqid();
    sql_query("insert into auth values ('{$this->auth_id}', {$this->pg_username}, now())", $db_central);
  }

  function load_anonymous() {
    global $default_anon_tags;

    $this->authenticated=false;
    $this->tags=new tags($default_user_tags);
    unset($this->username);
    unset($this->pg_username);
  }

  function valid_user() {
    return $this->auth_id;
  }

  function login_info() {
    if(!$this->authenticated) {
      return "<a href='javascript:login()'>".lang("user:login")."</a>";
    }
    else {
      return lang("user:logged_in_as").$this->username." (<a href='javascript:logout()'>".lang("user:logout")."</a>)";
    }
  }

  function transfer_user_info() {
    print "<script type='text/javascript'>\n";
    print "var current_user=new user(\"{$this->username}\", ".html_var_to_js($this->tags->data()).");\n";
    print "</script>\n";
  }

  function save() {
    global $db_central;

    if(!$this->authenticated)
      return;

    sql_query("update user_list set osm_tags=".
              array_to_hstore($this->tags->data()).
              " where username=$this->pg_username", $db_central);
  }

  function get_author() {
    $ret="";

    if($x=$this->tags->get("full_name"))
      $ret.=$x;
    else
      $ret.=$this->username;

    $ret.=" <{$this->username}@openstreetbrowser.org>";

    return $ret;
  }
};

function user_list() {
  global $db_central;

  $user_list=array();

  $res=sql_query("select * from user_list", $db_central);
  while($elem=pg_fetch_assoc($res)) {
    $user_list[]=$elem['username'];
  }

  call_hooks("user_list", $user_list);

  return $user_list;
}

function user_check_auth() {
  global $current_user;
  global $db_central;

  if(!$_COOKIE['auth_id']) {
    $current_user=new user();
    return;
  }
  
  $auth_id=$_COOKIE['auth_id'];
  $pg_auth_id=postgre_escape($auth_id);

  $res=sql_query("select * from auth where auth_id=$pg_auth_id", $db_central);
  if($elem=pg_fetch_assoc($res)) {
    $current_user=new user(array("username"=>$elem['username'], "auth_id"=>$auth_id), 1);
    sql_query("update auth set last_login=now() where auth_id=$pg_auth_id", $db_central);
  }
  else {
    $current_user=new user();
  }
}

function user_transfer_data() {
  global $current_user;

  $current_user->transfer_user_info();
}

register_hook("init", "user_check_auth");
register_hook("html_done", "user_transfer_data");
