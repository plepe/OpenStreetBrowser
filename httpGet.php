<?php
require('conf.php');
require('src/http_get_contents.php');

print http_get_contents($_REQUEST['url']);
