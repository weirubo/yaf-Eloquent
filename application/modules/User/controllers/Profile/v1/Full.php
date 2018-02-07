<?php
class Profile_V1_FullController extends BaseController {
	public function init() {
		Yaf_Dispatcher::getInstance()->disableView();
		parent::init();
	}
	public function indexAction() {
		$params = $this->getRequest()->getParams();
		jsonResult(200, 'success', $params, 'callback');
	}
}
