<?php
namespace App\Models;

// 定义模型
class User extends EloquentModel {
	// 与模型关联的数据表
	protected $table = 'user';
	// 该模型是否自动维护时间戳
	public $timestamps = false;
}
