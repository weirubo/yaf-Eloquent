<?php
define("APP_PATH",dirname(__FILE__));
$app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
$app->run();
