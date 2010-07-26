<?
include "../conf.php";
include "inc/lock.php";
include "inc/git_directory.php";

if(!$_FILES['data']) {
  print "<form action='git_upload.php' method='post' enctype='multipart/form-data'>\n";
  print "<input type='hidden' id='new'>\n";
  print "<input type='hidden' name='path' value='{$_REQUEST['path']}'>\n";
  print "<input type='hidden' name='commit_data' value='{$_REQUEST['commit_data']}'>\n";
  print "<input type='hidden' name='git_file' value='{$_REQUEST['git_file']}'>\n";
  print "<input type='hidden' name='file' value='{$_REQUEST['file']}'>\n";
  print "<input type='file' name='data' onChange='document.forms[0].submit()'>\n";
  print "</form>\n";
  exit;
}

$dir=new git_directory($_REQUEST['path']);
$dir->commit_continue($_REQUEST['commit_data']);
$git_file=$dir->get_file($_REQUEST['git_file']);
$git_file->save($_REQUEST['file'], file_get_contents($_FILES['data']['tmp_name']));

print "File successfully uploaded";
