<?
$rss=file_get_contents("http://blog.openstreetbrowser.org/rss.xml");

Header("content-type: text/xml; charset: utf-8");
print $rss;
