<?
$design_hidden=1;
?>
<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
user_check_auth();
call_hooks("ajax_start");

$dir=$data_dir->get_dir($_REQUEST['dir']);
$obj=$dir->get_obj($_REQUEST['obj']);
$file=$obj->load($_REQUEST['file'], $_REQUEST['version']);
Header("content-type: {$file['mime']}");
print $file['content'];
