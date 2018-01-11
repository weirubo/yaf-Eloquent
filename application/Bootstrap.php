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

