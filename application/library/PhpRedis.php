<?php
class PhpRedis {
	private $_HOST;
	private $_PORT;
	private $_TIMEOUT;
	private $_RESERVED;
	private $_RETRY_INTERVAL;
	private $_READ_TIMEOUT;

	private $_CLUSTER_HOST;
	private $_AUTH;
	private $_REDIS;

	public function __construct() {
		$this->_config = Yaf_Registry::get('config');
		$config = $this->_config->redis->toArray();
		$this->_HOST = $config['host'];
		$this->_PORT = $config['port'];
		$this->_TIMEOUT = $config['timeout'];
		$this->_RESERVED = $config['reserved'];
		$this->_RETRY_INTERVAL = $config['retry_interval'];
		$this->_READ_TIMEOUT = $config['read_timeout'];
		
		$this->_AUTH = $config['auth'];
		$this->_CLUSTER_HOST = explode(',', $config['cluster']['host']);
		if(count($this->_CLUSTER_HOST) < 2) {
			$this->_REDIS = new Redis();
			$this->_REDIS->connect($this->_HOST, $this->_PORT, $this->_TIMEOUT, $this->_RESERVED, $this->_RETRY_INTERVAL, $this->_READ_TIMEOUT);
		
			if(isset($this->_AUTH)) $this->_REDIS->auth($this->_AUTH);
		} else {
			$this->_REDIS = new RedisCluster(NULL, $this->_CLUSTER_HOST);
		}
		return $this->_REDIS;
	}
	public function ping() {
		try{
			return $this->_REDIS->ping() == '+PONG' ? true : false;
		}catch(Exception $e) {
			return 'Code:'.$e->getCode().';Message:'.$e->getMessage();
			exit;
		}
	}
	public function set() {
		$this->_REDIS->set('name', 'frankphper');
	}
	public function get() {
		echo $this->_REDIS->get('name');
	}
}
