<?
include "inc/lock.php";
include "inc/git_directory.php";

$git_dir=new git_directory("/home/osm/data/{$_REQUEST['directory']}");
$git_file=$git_dir->get_file($_REQUEST['git_file']);
$file=$git_file->load($_REQUEST['file'], $_REQUEST['version']);
Header("content-type: {$file['mime']}");
print $file['content'];
