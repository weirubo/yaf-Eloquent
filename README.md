# Yaf简介
* The Yet Another Framework (Yaf) 扩展是一个用来开发web应用的php框架。
* Yaf需要5.2.1及以上版本PHP，早期版本可能不能正常工作。
# yaf-Eloquent
* Yaf框架集成Eloquent ORM
# 运行环境
* Linux / Windows
* Nginx / Apcahe / Lighttpd
* MySQL
* PHP >= 5.2.1
# ORM
* Eloquent ORM
# 扩展
* Yaf
# Yaf安装
```
Yaf can be installed from source code by:

cd /path/to/yaf-src/
phpize
./configure
make
sudo make install
```
# Eloquent ORM 安装
```
composer install
```
# 重写规则
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
