#!/usr/bin/env bash
cd www
composer dump-autoload
cd ..
rsync -zavP -e 'ssh -p 922' ./www/ root@121.41.34.174:/d01/www/canyun-bll/


#ssh root@121.41.34.174 -p 922 <<EOF
#nginx -s reload
#EOF