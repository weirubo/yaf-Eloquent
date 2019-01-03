<?php
class Profile_V1_SimpleController extends BaseController {
	public function init() {
		Yaf\Dispatcher::getInstance()->disableView();
		parent::init();
	}
	public function indexAction() {
		echo 'this is simple profile v1';
	}
}
