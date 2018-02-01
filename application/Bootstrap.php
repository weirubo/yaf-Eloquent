<?php
/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

	private $config;

	public function _initVendor() {
		Yaf_Loader::import(APP_PATH . "/vendor/autoload.php");
	}
	
	public function _initFunction() {
		Yaf_Loader::import("Function.php");
	}

        public function _initConfig() {
                $this->config = Yaf_Application::app()->getConfig();
                Yaf_Registry::set("config", $this->config);
        }
	
	public function _initSession() {
		$config = Yaf_Registry::get('config');
		$saveHandler = $config->session->toArray();
		if($saveHandler['save_handler'] == 'redis') {
			ini_set('session.save_handler', 'redis');
			$path = $config->redis->toArray();
			ini_set('session.save_path', 'tcp://' . $path['host'] . ':' . $path['port'] . '?auth=' . $path['auth']);
		}
	}

	/**
	 * 自定义路由规则 参考RESTFul设计规则，实现支持版本号
	 * 路由格式：api.github.com/版本号/模块/分组/Controller/Action/参数key1/参数value1/参数key2/参数value2
	 * 示例：http://localhost/v1/User/Profile/Full/index/uid/1/username/admin/salary/10000
	 * 注意：版本号、模块、分组、Controller、Action，均需必填且严格要求顺序规则
	 */
	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		$method = $dispatcher->getRequest()->getMethod();
		$requestUri = $_SERVER['REQUEST_URI'];
		$requestArr = explode('/', ltrim($requestUri, '/'));
		$version = $requestArr[0];
		$module = $requestArr[1];
		$controller = $requestArr[2] . '_' . $version . '_' . $requestArr[3];
		$action = $requestArr[4];
		$params = array_slice($requestArr, 5);
		$keysArr = [];
		$valsArr = [];
		foreach($params as $k => $v) {
			if($k % 2 == 0) {
				array_push($keysArr, $v);
			} else {
				array_push($valsArr, $v);
			}
		}
		$paramsArr = array_combine($keysArr, $valsArr);
		$request = new Yaf_Request_Simple($method, $module, $controller, $action, $paramsArr);
		$dispatcher->setRequest($request);
	}

        public function _initDefaultName(Yaf_Dispatcher $dispatcher) {
                $dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
        }
	
	public function _initDefaultDb() {
		$capsule = new \Illuminate\Database\Capsule\Manager;
		$capsule->addConnection($this->config->database->toArray());
		$capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container));
		$capsule->setAsGlobal();
		$capsule->bootEloquent();

		class_alias('\Illuminate\Database\Capsule\Manager', 'DB');
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		$default = new defaultPlugin();
		$dispatcher->registerPlugin($default);
	}
}

