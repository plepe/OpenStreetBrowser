<?php
require('conf.php');
require('modulekit.php');

print http_get_contents($_REQUEST['url']);
