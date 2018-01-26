<?php
/**
 * @author weirubo
 * @link https://github.com/weirubo
 * @copyright Jan 11, 2018 - Dec 31, 2099
 * @version v0.01
 */
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
	/**
	 * @param $key string
	 * @param $value string
	 * @param $type int 0:set,1:setNx,2:getSet,3:append,4:setEx,5:setRange,default 0
	 * @param $num int ttl or offset
	 * @param $cover bool 0:no cover,1:cover
	 */
	public function set($key, $value, $type=0, $num=0, $cover=0) {
		switch ($type) {
			case 0:
				$result = $this->_REDIS->set($key, $value);
				break;
			case 1:
				$result = $this->_REDIS->setNx($key, $value);
				break;
			case 2:
				$result = $this->_REDIS->getSet($key, $value);
				break;
			case 3:
				$result = $this->_REDIS->append($key, $value);
				break;
			case 4:
				$ttl = $num;
				if($cover) $result = $this->_REDIS->setEx($key, $ttl, $value);
				$result = $this->_REDIS->setNx($key, $value);
				break;
			case 5:
				$offset = $num;
				$result = $this->_REDIS->setRange($key, $offset, $value);
				break;
		}
		return $result;
	}

	/**
	 * @param key string || array string:only one key,array:one more key
	 * @param start int
	 * @param $end int
	 */
	public function get($key = null, $start = null, $end = null) {
		if($key == null) $result = null;
		if(isset($key) && is_array($key)) {
			$result = $this->_REDIS->mGet($key);
		} else {
			if(isset($start) && isset($end)) $result = $this->_REDIS->getRange($key, $start, $end);
			$result = $this->_REDIS->get($key);
		}
		return $result;
	}

	/**
	 * @param key array || string array:one more key,string:only one key
	 * @return Long Number of keys deleted.
	 */
	public function del($key = []) {
		return $this->_REDIS->delete($key);
	}
	
	/**
	 * @param key array || string array:one more key,string:only one key
	 * @param cover int 0:mSetNx,1:mSet
	 * @param Bool TRUE in case of success, FALSE in case of failure.
	 */
	public function mset($key = [], $cover = 0) {
		if(count($key) > 1 && array_key_exists('cover', $key) && $key['cover'] > 0) $result = $this->_REDIS->mSet($key);
		$result = $this->_REDIS->mSetNx($key);
		return $result;
	}

	/**
	 * @param key string
	 * @param step int || float
	 * @return INT or Float the new value
	 */
	public function incr($key, $step = 1) {
		if($step != 1) {
			if(!is_float($step)) $result = $this->_REDIS->incrBy($key, $step);
			$result = $this->_REDIS->incrByFloat($key, $step);
		} else {
			$result = $this->_REDIS->incr($key);
		}
		return $result;
	}
	
	/**
	 * @param key string
	 * @param step int
	 * @return INT the new value
	 */
	public function decr($key, $step = 1) {
		if($step == 1) return $this->_REDIS->decr($key);
		return $this->_REDIS->decrBy($key, $step);
	}

	/**
	 * @param key sting
	 * @return INTEGER
	 */
	public function strlen($key) {
		return $this->_REDIS->strlen($key);
	}

	/**
	 * @param key string STRING: pattern, using '*' as a wildcard.
	 * @return Array of STRING: The keys that match a certain pattern.
	 */
	public function keys($key) {
		return $this->_REDIS->keys($key);
	}

	/**
	 * @param key string
	 * @return LONG: The time to live in seconds. If the key has no ttl, -1 will be returned, and -2 if the key doesn't exist.
	 */
	public function ttl($key) {
		return $this->_REDIS->ttl($key);
	}

	/**
	 * @param key string
	 * @return BOOL: If the key exists, return TRUE, otherwise return FALSE.
	 */
	public function exists($key) {
		return $this->_REDIS->exists($key);
	}
	
	/**
	 * @param key int 
	 * @param ttl int seconds || Unix timestamp
	 * @param BOOL: TRUE in case of success, FALSE in case of failure.
	 */
	public function expire($key, $ttl = 0) {
		if (strlen($ttl) >= 10) return $this->_REDIS->expireAt($key, $ttl);
		return $this->_REDIS->expire($key, $ttl);
	}
	/**
	 * @param srckey string
	 * @param dstkey string
	 * @param cover int 0:cover, 1:no cover
	 */
	public function rename($srckey, $dstkey, $cover = 0) {
		if($cover) $result = $this->_REDIS->rename($srckey, $dstkey);
		$result = $this->_REDIS->renameNx($srckey, $dstkey);
		return $result;
	}
	
	/**
	 * @param None
	 * @return STRING: an existing key in redis.
	 */
	public function randomkey() {
		return $this->_REDIS->randomkey();
	}

	/**
	 * @param key string
	 * @return BOOL: TRUE if a timeout was removed, FALSE if the key didn’t exist or didn’t have an expiration timer.
	 */
	public function persist($key) {
		return $this->_REDIS->persist($key);	
	}

	/**
	 * @param key string
	 * @return value 0:none,1:string,2:set,3:list,4:zset,5:hash
	 */
	public function type($key) {
		return $this->_REDIS->type($key);
	}
}
