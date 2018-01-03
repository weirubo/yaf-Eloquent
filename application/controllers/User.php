<?php
use App\Models\User;

class UserController extends Yaf_Controller_Abstract {
        public function init() {
                Yaf_Dispatcher::getInstance()->disableView();
        }
	public function indexAction() {
		$userCount = User::count();
		echo $userCount;
	}
}
