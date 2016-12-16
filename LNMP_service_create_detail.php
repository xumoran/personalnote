<?php
echo '

LNMP ������װ����
#��׼�ⰲװ����������׼����һЩ�����ĵײ�⣬�кܶ����������������Щ�ײ��
#step 1:
yum -y install wget make vim install gcc gcc-c++ ncurses ncurses-devel autoconf libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel libxml2 libxml2-devel zlib zlib-devel glibc glibc-devel glib2 glib2-devel bzip2 bzip2-devel curl curl-devel e2fsprogs e2fsprogs-devel krb5 krb5-devel libidn libidn-devel openssl openssl-devel openldap openldap-devel nss_ldap openldap-clients openldap-servers pcre pcre-devel zlip zlip-devel
#���� libxml2 Ҳ����������ʹ��wget
#wget http://xmlsoft.org/sources/libxml2-2.9.0.tar.gz
cp /home/linuxsoft/libxml2-2.9.0.tar.gz /usr/local/webserver/libxml2-2.9.0.tar.gz
tar zxvf libxml2-2.9.0.tar.gz
cd libxml2-2.9.0
./configure
make&&make install
#libmcrypt��װ
#libmcrypt �������� �����㷨��չ��(֧��DES, 3DES, RIJNDAEL, Twofish, IDEA, GOST, CAST-256, ARCFOUR, SERPENT, SAFER+���㷨)
#wget ftp://mcrypt.hellug.gr/pub/crypto/mcrypt/libmcrypt/libmcrypt-2.5.7.tar.gz
cp /home/linuxsoft/libmcrypt-2.5.7.tar.gz /usr/local/webserver/libmcrypt-2.5.7.tar.gz
tar zxvf libmcrypt-2.5.7.tar.gz
cd libmcrypt-2.5.7
./configure
make && make install
#MYSQL ��װ&����
#step2: ��װ
#wget http://mirror.neu.edu.cn/mysql/Downloads/MySQL-5.6/mysql-5.6.16-linux-glibc2.5-x86_64.tar.gz
cp /home/linuxsoft/mysql-5.6.12-linux-glibc2.5-x86_64.tar.gz /usr/local/webserver/mysql-5.6.12-linux-glibc2.5-x86_64.tar.gz
tar zxvf mysql-5.6.12-linux-glibc2.5-x86_64.tar.gz
mv mysql-5.6.16 mysql
#MYSQL ����
groupadd mysql
useradd -r -g mysql mysql
cd /usr/local/webserver/mysql
chown -R mysql .
chgrp -R mysql .
scripts/mysql_install_db --user=mysql
chown -R root .
chown -R mysql data
cp support-files/my-default.cnf /etc/my.cnf
#�޸�mysql.server�ű�/usr/local/webserver/mysql/support-files/
#��
basedir=
datadir=
#��Ϊ
basedir=/usr/local/webserver/mysql
datadir=/usr/local/webserver/mysql/data
#���������ű�
cd /usr/local/webserver/mysql
cp support-files/mysql.server /etc/init.d/mysql
#����mysql
service mysql start
service mysql stop
service mysql restart
#���û�������
#������Ҫ������PATH��Ҫ������ֱ�ӵ���mysql�޸�/etc/profile�ļ������ļ�ĩβ���
export PATH="/usr/local/webserver/mysql/bin:$PATH"
#�ر��ļ���������������������������Ч
source /etc/profile



#step 3��NGINX ��װ&����
#��װ
#wget http://nginx.org/download/nginx-1.4.7.tar.gz
cp /home/linuxsoft/nginx-1.5.1.tar.gz /usr/local/webservice/nginx-1.5.1.tar.gz
tar zxvf nginx-1.5.1.tar.gz
cd nginx-1.5.1
./configure --prefix=/usr/local/webserver/nginx
make && make install
#���������ű�
#ÿ������nginx��Ҫ�ҵ�nginx������Ŀ¼���������ɺ��鷳������������nginx������Ŀ¼������ִ��nginx��������ֹͣ�������Ľű�
#ִ�����������д�ű�
vi /etc/init.d/nginx
#�ű�
#!/bin/sh
#
# nginx - this script starts and stops the nginx daemin
#
# chkconfig: - 85 15
# description: Nginx is an HTTP(S) server, HTTP(S) reverse \
# proxy and IMAP/POP3 proxy server
# processname: nginx
# config: /usr/local/webserver/nginx/conf/nginx.conf
# pidfile: /usr/local/webserver/nginx/logs/nginx.pid

# Source function library.
. /etc/rc.d/init.d/functions

# Source networking configuration.
. /etc/sysconfig/network

# Check that networking is up.
[ "$NETWORKING" = "no" ] && exit 0

nginx="/usr/local/webserver/nginx/sbin/nginx"
prog=$(basename $nginx)

NGINX_CONF_FILE="/usr/local/webserver/nginx/conf/nginx.conf"

lockfile=/var/lock/subsys/nginx

start() {
 [ -x $nginx ] || exit 5
 [ -f $NGINX_CONF_FILE ] || exit 6
 echo -n $"Starting $prog: "
 daemon $nginx -c $NGINX_CONF_FILE
 retval=$?
 echo
 [ $retval -eq 0 ] && touch $lockfile
 return $retval
}

stop() {
 echo -n $"Stopping $prog: "
 killproc $prog -QUIT
 retval=$?
 echo
 [ $retval -eq 0 ] && rm -f $lockfile
 return $retval
}

restart() {
 configtest || return $?
 stop
 start
}

reload() {
 configtest || return $?
 echo -n $"Reloading $prog: "
 killproc $nginx -HUP
 RETVAL=$?
 echo
}

force_reload() {
 restart
}

configtest() {
 $nginx -t -c $NGINX_CONF_FILE
}

rh_status() {
 status $prog
}

rh_status_q() {
 rh_status >/dev/null 2>&1
}

case "$1" in
 start)
 rh_status_q && exit 0
 $1
 ;;
 stop)
 rh_status_q || exit 0
 $1
 ;;
 restart|configtest)
 $1
 ;;
 reload)
 rh_status_q || exit 7
 $1
 ;;
 force-reload)
 force_reload
 ;;
 status)
 rh_status
 ;;
 condrestart|try-restart)
 rh_status_q || exit 0
 ;;
 *)
 echo $"Usage: $0 {start|stop|status|restart|condrestart|try-restart|reload|force-reload|configtest}"
 exit 2
esac
#�޸�nginx�ű�Ȩ��
 chmod +x /etc/init.d/nginx
#��ӵ�ϵͳ����
 /sbin/chkconfig nginx on
#�����û��Լ�www����
useradd -g www www
#��NGINX ����www�û��Լ�www����
 chown -R www:www /usr/local/webserver/nginx
#ʹ�����������nginx����
 service nginx start
 service nginx stop
 service nginx restart
 service nginx reload

 /etc/init.d/nginx start
 /etc/init.d/nginx stop
 /etc/init.d/nginx restart
 /etc/init.d/nginx reload

#step 4��PHP ��װ&����

#��װ
#���밲װǰ��׼��
#��ʾ��Ĭ�ϵ�php��װ��gd��֧��jpg��ֻ֧��gif��png��bmp����������Ҫ��װgd��
#wget http://www.boutell.com/gd/http/gd-2.0.33.tar.gz
cp /home/linuxsoft/gd-2.0.35.tar.gz /usr/local/webserver/gd-2.0.35.tar.gz
tar zxvf gd-2.0.35.tar.gz
cd gd-2.0.35
./configure --prefix=/usr/local/webserver/gd2/
make && make install
ע���ڰ�װphp5.4��ʱ�����׳���
data undefind
����php5.4��bug ��Ҫ�� /usr/local/webserver/gd2/include/gd_io.h�����������
typedef struct gdIOCtx
{
 int (*getC) (struct gdIOCtx *);
 int (*getBuf) (struct gdIOCtx *, void *, int);

 void (*putC) (struct gdIOCtx *, int);
 int (*putBuf) (struct gdIOCtx *, const void *, int);

 /* seek must return 1 on SUCCESS, 0 on FAILURE. Unlike fseek! */
 int (*seek) (struct gdIOCtx *, const int);

 long (*tell) (struct gdIOCtx *);

 void (*gd_free) (struct gdIOCtx *);
 void (*data);
}
gdIOCtx;
#PHP ��װ
#wget http://www.php.net/get/php-5.3.26.tar.gz/from/us2.php.net/mirror
cp /home/linuxsoft/php-5.4.36.tar.gz /usr/local/webserver/php-5.4.36.tar.gz
tar zxvf php-5.4.36.tar.gz
cd php-5.4.36
./configure --prefix=/usr/local/webserver/php --enable-fpm --with-mysql=/usr/local/webserver/mysql \
--with-mysqli=/usr/local/webserver/mysql/bin/mysql_config --with-config-file-path=/usr/local/webserver/php \
--with-openssl --with-curl --enable-mbstring --with-zlib --enable-xml --with-gd=/usr/local/webserver/gd2/ --with-jpeg-dir \
--enable-bcmath --with-mcrypt --with-iconv --enable-pcntl --enable-shmop --enable-simplexml --enable-ftp
make && make install

cp php.ini-development /usr/local/webserver/php/php.ini

����
php(php.ini)
 �� ;date.timezone =
 ��Ϊ date.timezone = prc
php+nginx��nginx.conf��
 user www www;
 worker_processes 1;
 events {
 worker_connections 1024;
 }
 http {
 include mime.types;
 index index.php index.html index.htm;
 root /data/www;

 default_type application/octet-stream;
 sendfile on;
 keepalive_timeout 65;
 server {
 listen 80;
 server_name 192.168.51.67;
 if ( $host ~* (.*)\.(.*)\.(.*)){
 set $domain $1;
 }

 location ~ ^/(.*)/data/.*\.(php)?$
 {
 return 404;
 deny all;
 }

 location ~ ^/(.*)/public/.*\.(php)?$
 {
 return 404;
 deny all;
 }

 location ~ ^/(.*)/themes/.*\.(php)?$
 {
 return 404;
 deny all;
 }

 location ~ ^/(.*)/wap_themes/.*\.(php)?$
 {
 return 404;
 deny all;
 }

 #α��̬���ÿ�ʼ.....

 if ($request_uri ~ (.+?\.php)(|/.*)$ ){
 break; 
 }

 location / {
 autoindex on;
 send_timeout 1800;
 fastcgi_buffers 8 128k;
 fastcgi_intercept_errors on;
 #α��̬����
 if ( !-e $request_filename ) {
 rewrite ^/(.*)$ /index.php/$1 last;
 }
 }

 location ~ ^/shopadmin {
 rewrite ^/(.*)$ /index.php/$1 last;
 break;
 }

 #α��̬���ý���......

 error_page 500 502 503 504 /50x.html;
 location = /50x.html {
 root html;
 }

 location ~ \.php {
 include fastcgi_params;
 set $real_script_name $fastcgi_script_name;
 set $path_info "";
 set $real_script_name $fastcgi_script_name;
 if ($fastcgi_script_name ~ "^(.+\.php)(/.+)$") {
 set $real_script_name $1;
 set $path_info $2;
 }
 fastcgi_param SCRIPT_FILENAME $document_root$real_script_name;
 fastcgi_param SCRIPT_NAME $real_script_name;
 fastcgi_param PATH_INFO $path_info;
 fastcgi_pass 127.0.0.1:9000;
 fastcgi_index index.php;
 }


 }

 }
 
php+pathinfo��php.ini��
enable_dl = On
cgi.force_redirect = 0
cgi.fix_pathinfo=1
fastcgi.impersonate = 1
cgi.rfc2616_headers = 1
allow_url_fopen = On
����php-fpm�����ű�
��д�ű���vi /etc/init.d/php-fpm ��
#! /bin/sh

### BEGIN INIT INFO
# Provides: php-fpm
# Required-Start: $remote_fs $network
# Required-Stop: $remote_fs $network
# Default-Start: 2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: starts php-fpm
# Description: starts the PHP FastCGI Process Manager daemon
### END INIT INFO

prefix=/usr/local/webserver/php

php_fpm_BIN=${prefix}/sbin/php-fpm
php_fpm_CONF=${prefix}/etc/php-fpm.conf
php_fpm_PID=${prefix}/var/run/php-fpm.pid


php_opts="--fpm-config $php_fpm_CONF"
php_pid="--pid $php_fpm_PID"

wait_for_pid () {
 try=0

 while test $try -lt 35 ; do

 case "$1" in
 'created')
 if [ -f "$2" ] ; then
 try=''
 break
 fi
 ;;

 'removed')
 if [ ! -f "$2" ] ; then
 try=''
 break
 fi
 ;;
 esac

 echo -n .
 try=`expr $try + 1`
 sleep 1

 done

}

case "$1" in
 start)
 echo -n "Starting php-fpm "

 $php_fpm_BIN $php_opts $php_pid

 if [ "$?" != 0 ] ; then
 echo " failed"
 exit 1
 fi

 wait_for_pid created $php_fpm_PID

 if [ -n "$try" ] ; then
 echo " failed"
 exit 1
 else
 echo " done"
 fi
 ;;

 stop)
 echo -n "Gracefully shutting down php-fpm "

 if [ ! -r $php_fpm_PID ] ; then
 echo "warning, no pid file found - php-fpm is not running ?"
 exit 1
 fi

 kill -QUIT `cat $php_fpm_PID`

 wait_for_pid removed $php_fpm_PID

 if [ -n "$try" ] ; then
 echo " failed. Use force-exit"
 exit 1
 else
 echo " done"
 fi
 ;;

 force-quit)
 echo -n "Terminating php-fpm "

 if [ ! -r $php_fpm_PID ] ; then
 echo "warning, no pid file found - php-fpm is not running ?"
 exit 1
 fi

 kill -TERM `cat $php_fpm_PID`

 wait_for_pid removed $php_fpm_PID

 if [ -n "$try" ] ; then
 echo " failed"
 exit 1
 else
 echo " done"
 fi
 ;;

 restart)
 $0 stop
 $0 start
 ;;

 reload)

 echo -n "Reload service php-fpm "

 if [ ! -r $php_fpm_PID ] ; then
 echo "warning, no pid file found - php-fpm is not running ?"
 exit 1
 fi

 kill -USR2 `cat $php_fpm_PID`

 echo " done"
 ;;

 *)
 echo "Usage: $0 {start|stop|force-quit|restart|reload}"
 exit 1
 ;;

esac
����ű�ִ��Ȩ��
 cd /usr/local/webserver/php/etc && cp php-fpm.conf.default php-fpm.conf
 chmod +x /etc/init.d/php-fpm
���ÿ�������
 /sbin/chkconfig php-fpm on
#ʹ�����������php����
 service php-fpm start
 service php-fpm stop
 service php-fpm restart
#���php+nginx�Ƿ����óɹ�
#��nginx.conf�ļ������������ҵ�php����Ŀ¼/www,�ڴ�Ŀ¼�½����ļ�phpinfo.php��Ȼ������#�鿴���ݡ�phpinfo();
#step 5 :ZendGuardLoader��װ
cp /home/linuxsoft/ZendGuardLoader-70429-PHP-5.4-linux-glibc23-x86_64.tar.gz /usr/local/webserver/ZendGuardLoader-70429-PHP-5.4-linux-glibc23-x86_64.tar.gz
tar zxvf ZendGuardLoader-70429-PHP-5.4-linux-glibc23-x86_64.tar.gz
#�༭php.ini�ļ�������һ������
[Zend]
zend_extension="/usr/local/webserver/ZendGuardLoader/php-5.4.x/ZendGuardLoader.so"
zend_loader.enable=1
zend_loader.disable_licensing=0
zend_loader.obfuscation_level_support=3
zend_loader.license_path="/var/www/bbc/config/developer.zl"
#����php 
service php-fpm restarr
#���� OK ��
�������ˣ�������������������������������



';


exit;
?>