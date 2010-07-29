<?
$design_hidden=1;
include_once "../conf.php";
include_once "inc/global.php";
$sql=pg_connect("dbname=$db_name user=$db_user password=$db_passwd host=$db_host");
user_check_auth();
call_hooks("ajax_start");

if(!$_FILES['data']) {
  print "<form action='git_upload.php' method='post' enctype='multipart/form-data'>\n";
  print "<input type='hidden' id='new'>\n";
  print "<input type='hidden' name='dir' value='{$_REQUEST['dir']}'>\n";
  print "<input type='hidden' name='obj' value='{$_REQUEST['obj']}'>\n";
  print "<input type='hidden' name='file' value='{$_REQUEST['file']}'>\n";
  print "<input type='hidden' name='commit_id' value='{$_REQUEST['commit_id']}'>\n";
  print "<input type='file' name='data' onChange='document.forms[0].submit()'>\n";
  print "</form>\n";
  exit;
}

$dir=$data_dir->get_dir($_REQUEST['dir']);
$data_dir->commit_continue($_REQUEST['commit_id']);
$obj=$dir->get_obj($_REQUEST['obj']);
$obj->save($_REQUEST['file'], file_get_contents($_FILES['data']['tmp_name']));

print "File successfully uploaded";
