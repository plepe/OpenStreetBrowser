<?
$f=fopen("/tmp/foobar.xml", "w");
$postdata = file_get_contents("php://input");
fprintf($f, $postdata);
fclose($f);
