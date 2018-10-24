<?php
define('APP_PATH', realpath(dirname(__FILE__) . '/../'));
date_default_timezone_set("PRC");
$app = new Yaf\Application(APP_PATH . "/conf/application.ini");
// 解决json_encode()浮点数溢出
ini_set('serialize_precision', 16);
if($app->environ() == 'development') ini_set('display_errors', 1); error_reporting(E_ERROR);
$app->bootstrap()->run();
