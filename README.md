# yaf-Eloquent
# 运行环境
* Linux
* Nginx
* MySQL
* PHP
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
