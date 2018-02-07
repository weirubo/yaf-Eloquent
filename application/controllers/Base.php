<?php
class BaseController extends Yaf_Controller_Abstract {
	public function init() {
		// 密钥验证，如果需要使用请去掉注释开启验证
		// $this->checkRequest();
	}
	
	/**
	 * 简单实现一个密钥验证函数
	 * 时效性限制以秒为单位
	 */
	public function checkRequest() {
		$params = $this->getRequest()->getParams();
		// 时效性验证
		$time = $params['time'];
		$valiTime = time() - $time;
		if($valiTime > 300) jsonResult(403, 'timeout', []);

		// 密钥验证
		$secretKey = $params['secretKey'];
		foreach($params as $key => $value) {
			if($value == $secretKey) unset($params[$key]);
		}
		ksort($params);
		$requestUrl = $_SERVER['SERVER_NAME'];
		foreach($params as $k => $v) {
			$requestUrl .= 	'/' . $k . '/' . $v;
		}
		$config = Yaf_Registry::get('config');
		$configArr = $config->toArray();
		$saltKey = $configArr['saltKey'];
		// 生成密钥
		$makeSecret = md5($requestUrl . '/saltKey/' . $saltKey);
		if($makeSecret != $secretKey) jsonResult(401, 'Unauthorized', []);
	}
} 
