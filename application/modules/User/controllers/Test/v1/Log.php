<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Test_V1_LogController extends Yaf_Controller_Abstract {
	public function init() {
		Yaf\Dispatcher::getInstance()->disableView();
	}
	public function indexAction() {
		// Create the logger
		$logger = new Logger('my_logger');
		// Now add some handlers
		$logger->pushHandler(new StreamHandler(APP_PATH . '/public/logs/my_app.log', Logger::DEBUG));
		$logger->pushHandler(new FirePHPHandler());

		// You can now use your logger
		$logger->info('My logger is now ready');
		$logger->info('Adding a new user', array('username' => 'Seldaek'));
		$logger->info('Adding a new user', array('uid' => 1, 'username' => 'frankphper'));
	}
}
