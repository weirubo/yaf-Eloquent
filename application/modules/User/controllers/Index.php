<?php
use App\Models\User;

class IndexController extends Yaf_Controller_Abstract {
	public function init() {
		Yaf_Dispatcher::getInstance()->disableView();
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
