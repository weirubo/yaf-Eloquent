<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class LogController extends Yaf_Controller_Abstract {
	public function init() {
		Yaf_Dispatcher::getInstance()->disableView();
	}

	public function indexAction() {
		// Create the logger
		$logger = new Logger('my_logger');
		// Now add some handlers
		$logger->pushHandler(new StreamHandler(APP_PATH . '/public/logs/my_app.log', Logger::DEBUG));
		$logger->pushHandler(new FirePHPHandler());

		// You can now use your logger
		$logger->info('My logger is now ready');
	}
}
