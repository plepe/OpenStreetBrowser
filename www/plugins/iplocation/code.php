<?
if($text=@file_get_contents("http://iplocationtools.com/ip_query.php?ip=$_SERVER[REMOTE_ADDR]")) {
  $dom=new DOMDocument();
  set_error_handler('maskErrors');
  $dom->loadXML($text);
  restore_error_handler();
  $my_lat=$dom->getElementsByTagName("Latitude")->item(0)->textContent;
  $my_lon=$dom->getElementsByTagName("Longitude")->item(0)->textContent;
}

