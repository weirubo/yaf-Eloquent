<?php
define('APP_PATH', realpath(dirname(__FILE__) . '/../'));
date_default_timezone_set("PRC");
$app = new Yaf_Application(APP_PATH . "/conf/application.ini");
if($app->environ() == 'product') ini_set('display_errors', 1); error_reporting(E_ALL);
$app->bootstrap()->run();
