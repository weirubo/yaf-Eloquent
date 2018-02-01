<?php
class Profile_V1_FullController extends BaseController {
	public function indexAction() {
		$params = $this->getRequest()->getParams();
		var_dump($params);
	}
}
