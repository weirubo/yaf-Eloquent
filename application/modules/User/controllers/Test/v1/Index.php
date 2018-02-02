<?php
use App\Models\User;

class Test_V1_IndexController extends BaseController {
	public function init() {
		Yaf_Dispatcher::getInstance()->disableView();
		parent::init();
	}
	// session
	public function testSessionAction() {
		session_start();
		$_SESSION['sex'] = 'man';
		var_dump($_SESSION);
	}
	
	public function testValiAction() {
		echo 123;		
	}

	public function testFuncAction() {
		$result = jsonResult(200, 'success', ['id' => 1, 'name' => 'frank']);
	}
	// redis
	public function setRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->set($params['name'], $params['age'] );
		echo $result;
	}
	public function getRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->get($params['name']);
		echo $result;
	}
	public function delRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->del($params['name']);
		echo $result;
	}
	public function msetRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->mset($params);
		echo $result;
	}
	public function incrRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->incr($params['name'], $params['step']);
		echo $result;
	}
	public function decrRedisAction() {
		$params = $this->getRequest()->getParams();
		$step = isset($params['step']) ? $params['step'] : 1;
		$redis = new PhpRedis();
		$result = $redis->decr($params['name'], $step);
		echo $result;
	}
	public function strlenRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->strlen($params['name']);
		echo $result;
	}
	public function keysRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->keys($params['name']);
		var_dump($result);
	}
	public function ttlRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->ttl($params['name']);
		echo $result;
	}
	public function existsRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->exists($params['name']);
		echo $result;
	}
	public function expireRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->expire($params['name'], $params['ttl']);
		echo $result;
	}
	public function renameRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->rename($params['name'], $params['newname'], $params['cover']);
		echo $result;
	}
	public function randomkeyRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->randomkey();
		echo $result;
	}
	public function persistRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->persist($params['name']);
		echo $result;
	}
	public function typeRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->type($params['name']);
		var_dump($result);
	}
	public function hsetRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->hset($params['key'], $params['hashKey'], $params['value'], $params['cover']);
		var_dump($result);
	}
	public function hgetRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->hget($params['key'], $params['type'], $params['hashKey']);
		var_dump($result);
	}
	public function saddRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->sadd($params['key'], $params['member']);
		echo $result;
	}
	public function scardRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->scard($params['key']);
		echo $result;
	}
	public function sdiffRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		// $result = $redis->sdiff($params['key']);
		$result = $redis->sdiff(['k1', 'k2']);
		var_dump($result);
	}
	public function smembersRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->smembers($params['key']);
		var_dump($result);
	}
	public function sismemberRedisAction() {
		$params = $this->getRequest()->getParams();
		$redis = new PhpRedis();
		$result = $redis->sismember($params['key'], $params['member']);
		echo $result;
	}
	// 运行原生SQL查询
	public function selectAction() {
		$users = DB::select('select * from user where id=1');
		var_dump($users);
	}
	// 查询构造器
	public function selAction() {
		$users = DB::table('user')->get();
		var_dump($users);
	}
	// 取回多个模型 
	public function indexAction() {
		$users = User::all()->toArray();
		var_dump($users);
	}
	// 增加额外的限制
	public function getAction() {
		$users = User::where('age', 18)->get()->toArray();
		var_dump($users);
	}
	// 取回单个模型
	public function findAction() {
		$user = User::find(1)->toArray();
		var_dump($user);
	}
	public function firstAction() {
		$user = User::where('age', 28)->first()->toArray();
		var_dump($user);
	}
	public function findsAction() {
		$user = User::find([1,2])->toArray();
		var_dump($user);
	}
	// 取回集合
	public function countAction() {
		$count = User::count();
		echo $count;
	}
	public function maxAction() {
		$max = User::max('age');
		echo $max;
	}
	public function sumAction() {
		$sum = User::sum('age');
		echo $sum;
	}
	// 基本添加
	public function saveAction() {
		$user = new User;
		$user->name = 'frank';
		$user->age = 18;
		$user->save();
	}
	// 基本更新
	public function saveUpdateAction() {
		$user = User::find(1);
		$user->age = 28;
		$user->save();
	}
	// 批量更新
	public function updateAction() {
		User::where('age', 28)->update(['name' => 'frankphper']);
	}
}
