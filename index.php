<?php

define('APPLICATION_PATH', dirname(__FILE__));
define('BASE_PATH',"http://2.xyresume.applinzi.com");
$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");
$application->bootstrap()->run();
?>
