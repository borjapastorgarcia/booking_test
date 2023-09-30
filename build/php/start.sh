#!/usr/bin/env sh

USER_ID=${LOCAL_USER_ID:-9001}

echo "Starting with UID : $USER_ID"
adduser -s /bin/sh -u $USER_ID -g www-data -h /home/user user
export HOME=/home/user

php-fpm