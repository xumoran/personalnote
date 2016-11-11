php 5.6安装
系统环境：
CentOS 6.5 / 7.0 x86_64 
# wget http://cn2.php.net/distributions/php-5.6.0.tar.xz
# xz -d php-5.6.0.tar.xz
# tar xf php-5.6.0.tar -C /usr/local/src/
添加 epel 源
# rpm -Uvh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
安装依赖
# yum install gcc bison bison-devel zlib-devel libmcrypt-devel mcrypt mhash-devel openssl-devel libxml2-devel libcurl-devel bzip2-devel readline-devel libedit-devel sqlite-devel
注：如果你使用的 epel 7 的源，可能会没有 mcrypt mhash mhash-devel 几个包，在 http://dl.fedoraproject.org/pub/epel/6/x86_64/ 中下载，使用 yum localinstall xxx.rpm 或 rpm -Uvh xxx.rpm 手动安装即可。

创建 www 用户
# groupadd www
# useradd -g www -s /sbin/nologin -M www
编译安装
# cd /usr/local/src/php-5.6.0/

# ./configure \
--prefix=/usr/local/php56 \
--with-config-file-path=/usr/local/php56/etc \
--enable-inline-optimization \
--disable-debug \
--disable-rpath \
--enable-shared \
--enable-opcache \
--enable-fpm \
--with-fpm-user=www \
--with-fpm-group=www \
--with-mysql=mysqlnd \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--with-gettext \
--enable-mbstring \
--with-iconv \
--with-mcrypt \
--with-mhash \
--with-openssl \
--enable-bcmath \
--enable-soap \
--with-libxml-dir \
--enable-pcntl \
--enable-shmop \
--enable-sysvmsg \
--enable-sysvsem \
--enable-sysvshm \
--enable-sockets \
--with-curl \
--with-zlib \
--enable-zip \
--with-bz2 \
--with-readline
参数说明：

""" 安装路径 """
--prefix=/usr/local/php56 \
""" php.ini 配置文件路径 """
--with-config-file-path=/usr/local/php56/etc \
""" 优化选项 """
--enable-inline-optimization \
--disable-debug \
--disable-rpath \
--enable-shared \
""" 启用 opcache，默认为 ZendOptimizer+(ZendOpcache) """
--enable-opcache \
""" FPM """
--enable-fpm \
--with-fpm-user=www \
--with-fpm-group=www \
""" MySQL """
--with-mysql=mysqlnd \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
""" 国际化与字符编码支持 """
--with-gettext \
--enable-mbstring \
--with-iconv \
""" 加密扩展 """
--with-mcrypt \
--with-mhash \
--with-openssl \
""" 数学扩展 """
--enable-bcmath \
""" Web 服务，soap 依赖 libxml """
--enable-soap \
--with-libxml-dir \
""" 进程，信号及内存 """
--enable-pcntl \
--enable-shmop \
--enable-sysvmsg \
--enable-sysvsem \
--enable-sysvshm \
""" socket & curl """
--enable-sockets \
--with-curl \
""" 压缩与归档 """
--with-zlib \
--enable-zip \
--with-bz2 \
""" GNU Readline 命令行快捷键绑定 """
--with-readline
如果你的 Web Server 使用的 Apache 请添加类似：--with-apxs2=/usr/local/apache-xx/bin/apxs 参数。

关于 mysqlnd 请查看 什么是 PHP 的 MySQL Native 驱动? 或查看 MySQL 官方介绍：MySQL native driver for PHP， 或 Installation on Unix。

PHP 5.6 內建了 phpdbg 交互式调试器，通过 --enable-phpdbg 开启，会在 PREFIX/bin 目录下产生一个 phpdbg 命令，感兴趣的可以试一下。

更多编译参数请使用 ./configure --help 查看。

# make -j8
# make install
如果想重新安装：

# make clean
# make clean all

# ./configure ...
# make -j8
# make install
配置 PHP
配置文件：

# cp php.ini-development /usr/local/php56/etc/php.ini
php-fpm 服务

# cp /usr/local/php56/etc/php-fpm.conf.default /usr/local/php56/etc/php-fpm.conf
# cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm56
# chmod +x /etc/init.d/php-fpm56
启动 php-fpm

# service php-fpm56 start
Starting php-fpm done
php-fpm 可用参数 start|stop|force-quit|restart|reload|status

添加 PHP 命令到环境变量
编辑 ~/.bash_profile，将：

PATH=$PATH:$HOME/bin
改为：
PATH=$PATH:$HOME/bin:/usr/local/php56/bin
使 PHP 环境变量生效：

# . ~/.bash_profile
查看看 PHP 版本

# php -v
PHP 5.6.0 (cli) (built: Sep 23 2014 03:44:18) 
Copyright (c) 1997-2014 The PHP Group
Zend Engine v2.6.0, Copyright (c) 1998-2014 Zend Technologies



PHP扩展的安装（安装基本类似）
soap扩展安装
cd php-5.2.8/ext/soap && /usr/local/php/bin/phpize
#./configure --with-php-config=/usr/local/php/bin/php-config --enable-soap
#make
#make install
curl 扩展安装
cd php-5.2.8/ext/curl && /usr/local/php/bin/phpize


./configure --with-php-config=/usr/local/php/bin/php-config --with-curl=DIR
make && make install