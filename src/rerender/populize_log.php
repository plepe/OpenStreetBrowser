#!/usr/bin/php
<?
include "../../conf.php";
$sql=pg_connect("dbname=osb");// user=$db_user password=$db_passwd host=$db_host");

$f=fopen("/var/log/apache2/access.log.1", "r");
while($r=fgets($f)) {
  if(eregi("^([:0-9\.A-Z_\-]*) - - \[([0-9]+)/(.*)/([0-9]+)[0-9:]+ \+[0-9]+\] \"GET /tiles/([0-9A-Z_]+)/([0-9]+)/([0-9]*)/([0-9]*)\.png"
     , $r, $m)) {
    pg_query("select inc_access('$m[5]', $m[7], $m[8], $m[6], 0);");
  }
}


