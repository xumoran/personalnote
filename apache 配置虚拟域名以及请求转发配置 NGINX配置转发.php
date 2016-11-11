apache 配置虚拟域名以及请求转发配置 NGINX配置转发


在 httpd.conf 中，我们会发现类似如下的一行，是有关rewrite模块的，模块名是 mod_rewrite.so 。 

LoadModule rewrite_module lib/httpd/modules/mod_rewrite.so 
或 
LoadModule rewrite_module lib/apache2/modules/mod_rewrite.so 如果前面有#号，您要去掉。


设置DocumentRoot的Directory： 


在Apache 2.x 中，我们会看到 DocumentRoot设置的一行。这行就是存放网页程序的地方。比如LinuxSir.Org 存放在 /opt/www 目录中。那么我们就要设置 DocumentRoot为如下的。 

DocumentRoot "/opt/www" 然后我们再还要对 DocumentRoot做针对性的行为设置。在一般的情况下，httpd.conf 会给一个默认的。如果你要改 DocumentRoot的路径，同时也要改针对DocumentRoot的Directory的设置，也就是 

 

比如我们把DocumentRoot的路径改为了 "/opt/www"，那我们也要把Directory做针对性的行为设置也要改成这个路径。 

 

Options FollowSymLinks 
#AllowOverride None 注：把这行前面加#号，然后加下面的一行 ，也就是 AllowOverride ALL 
AllowOverride ALL 
Order allow,deny 
Allow from all 
 我们把AllowOverride 的参数设置为ALL，表示整台服务器上的，都支持URL规则重写。Apache 服务器要读每个网站根目录下的 .htaccess 文件。如果没有这个文件，或者这个文档没有定义任何关于URL重写的规则，则不会有任何效果。在一般的情况下，成熟的Web 服务器应用套件，都支持URL重写的，比如drupal和joomla 。当我们用这些程序时，会发现在安装包中有 .htaccess中有这个文件。我们把Apache配置好后，只是需要在这些程序的后台打开此功能就行了。 
若是windows环境，那么需要在本地绑定域名访问的IP
C:\Windows\System32\drivers\etc
hosts文件增加
127.0.0.1 你的域名















appache 请求转发配置

去掉 # （httpd.conf）
#LoadModule proxy_module modules/mod_proxy.so
#LoadModule proxy_http_module modules/mod_proxy_http.so
LISTEN 8080 （httpd.conf）
添加 8080 端口

添加（ProxyPass 和 ProxyPassReverse）配置 （ httpd-vhosts.conf）
 ServerAdmin webmaster@mtest.teegon.com
	ServerAlias teegon.com *.teegon.com 
	ProxyPass /buy http://mtest.teegon.com:8080/buy
	ProxyPassReverse /buy http://mtest.teegon.com:8080/buy
 DocumentRoot "D:/phpstudy/WWW/h5/public"
 ServerName mtest.teegon.com
 ErrorLog "logs/mtest.teegon.com-error.log"
 CustomLog "logs/mtest.teegon.com-access.log" common



 ServerAdmin webmaster@qrtest.teegon.com
 DocumentRoot "D:/phpstudy/WWW/website"
 ServerName mtest.teegon.com:8080
 ErrorLog "logs/qrtest.teegon.com-error.log"
 CustomLog "logs/qrtest.teegon.com-access.log" common



NGINX配置转发
 server {
 listen 9090
	 server_name 127.0.0.1;
 
 #charset koi8-r;
 
 #access_log logs/host.access.log main;
 
 location / {
 root html;
 index index.html index.htm;
 }
 #Proxy Settings
 location /mswcf {
 rewrite ^.+mswcf/?(.*)$ /$1 break;
 proxy_pass http://172.16.58.39:8080/;
 }
 location /uswcf {
 rewrite ^.+uswcf/?(.*)$ /$1 break;
 proxy_pass http://172.16.58.38:8080/;
 }
 location /cswcfw {
 rewrite ^.+cswcfw/?(.*)$ /$1 break;
 proxy_pass http://172.16.58.37/;
 } 
 #error_page 404 /404.html;
 }