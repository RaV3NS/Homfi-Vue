#!/bin/sh
#if [ ! -f /var/www/composer-done ]; then
#    composer --working-dir=/var/www/ install
#fi

#export

#if [ $PROD="YES" ]; then
    #git-crypt unlock /git.key
    #cp .docker/env.prod /var/www/.env
    #cat .docker/env.prod
    #cat /var/www/.env
#fi

#if [ $STAGE="YES" ]; then
#    cp .docker/env.stage /var/www/.env
#fi

chmod 777 /dev/pts/0

/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
