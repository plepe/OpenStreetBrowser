<?
$design_hidden=1;
include_once "../conf.php";
include_once "inc/global.php";
$sql=pg_connect("dbname=$db_name user=$db_user password=$db_passwd host=$db_host");
user_check_auth();
call_hooks("ajax_start");

$dir=$data_dir->get_dir($_REQUEST['dir']);
$obj=$dir->get_obj($_REQUEST['obj']);
$file=$obj->load($_REQUEST['file'], $_REQUEST['version']);
Header("content-type: {$file['mime']}");
print $file['content'];
