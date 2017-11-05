#!/bin/sh
set -e
if [ ! -f /www/nginx.rewrite ]
then
touch /www/nginx.rewrite
echo "/www/nginx.rewrite文件不存在,已自动创建"
fi


/usr/sbin/nginx && /usr/sbin/php-fpm && echo "=========server is running=========" && tail -f /var/log/nginx/access.log