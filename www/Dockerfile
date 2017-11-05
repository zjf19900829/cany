#FROM registry.cn-hangzhou.aliyuncs.com/keladi/php_nginx
#ADD www/ /www/
FROM php:7.0-apache
COPY www/ /var/www/html/
ENV API_KEY API_KEY
ENV API_SEC API_SEC
ENV ATTACH_UPLOAD_HOST http://oss-attach-upload.serv.keladi.net
EXPOSE 80
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/