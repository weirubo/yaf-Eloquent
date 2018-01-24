# Yaf简介
* The Yet Another Framework (Yaf) 扩展是一个用来开发web应用的php框架。
* Yaf需要5.2.1及以上版本PHP，早期版本可能不能正常工作。
* PHP7+需要使用Yaf3.0.0+版本。
# yaf-Eloquent简介
* Yaf框架集成Eloquent ORM
# 运行环境
* Linux / Windows
* Nginx / Apcahe / Lighttpd
* MySQL >= 5.7
* PHP >= 7.0.0（建议使用最新版本PHP7.2.1）
# ORM
* Eloquent ORM >= 5.5.0
# 扩展
* Yaf >= 3.0.0（建议使用最新版本Yaf3.0.6）
* phpredis >= 3.1.6 (可选安装)
# 项目部署步骤
### Yaf安装
```
Yaf can be installed from source code by:

cd /path/to/yaf-src/
phpize
./configure
make
sudo make install
```
### phpredis安装
```
phpize
./configure [--enable-redis-igbinary]
make && make install
```
### 修改php.ini
```
# 添加以下代码
[yaf]
extension=yaf.so
[redis]
extension=redis.so

# 重启php-fpm（以下两种方式任选其一）
/etc/init.d/php-fpm restart
kill -USR2 `cat /usr/local/php/var/run/php-fpm.pid`
```

### Eloquent ORM 安装
```
# 需要使用函数proc_open,proc_get_status，如禁用，请修改php.ini中disable_functions的值，去除proc_open,proc_get_status，重启php-fpm，重启方法见上。
# 执行以下代码安装 Eloquent ORM
composer install
# 如果未安装composer，请先安装composer再执行`composer install`
```
### 安装composer
可参考[Composer](http://docs.phpcomposer.com/00-intro.html)
### 导入MySQL数据库test.sql
```
source test.sql
```

### 重写规则，修改nginx.conf并执行`/usr/local/nginx/sbin/nginx -s reload`
```
#for apache (.htaccess)
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .* index.php

#for nginx
server {
  listen ****;
  server_name  domain.com;
  root   document_root;
  index  index.php index.html index.htm;

  if (!-e $request_filename) {
    rewrite ^/(.*)  /index.php?$1 last;
  }
}

#for lighttpd
$HTTP["host"] =~ "(www.)?domain.com$" {
  url.rewrite = (
     "^/(.+)/?$"  => "/index.php/$1",
  )
}
```

### 大功告成O(∩_∩)O哈哈~浏览器访问地址查看，enjoy it.
