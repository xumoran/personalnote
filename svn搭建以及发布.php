linux 环境下subversion-1.6.1.tar.gz
	tar zxvf subversion.tar.gz -C svn161
进入解压后的subversion目录：
	cd /usr/local/svn161
创建SVN安装目录：
	mkdir sr/local/svn
编译安装subversion（svn有两种模式，一种FSFS为默认模式，另一种为BDB模式，建议使用FSFS。）：
	./configure --prefix=/usr/local/svn
	make
	make install
测试SVN是否安装成功：
	/usr/local/svn/bin/svnserve --version
	出现如下信息，则证明安装成功：
	svnserve，版本 1.6.1 (r37116)
	编译于 Mar 4 2011，10:07:53
将SVN命令添加到环境变量：
	vi /etc/profile
	写入如下两句
	PATH=$PATH:/usr/local/svn/bin
	export PATH
	保存退出
	source /etc/profile
建立版本库目录（这个和安装目录请区分开，以下讲的是版本库此处建立于/opt/svndata文件夹下）：
	mkdir -p /usr/local/svndata/project_01
建立svn版本库：
	svnadmin create /usr/local/svndata/project_01/
配置SVN项目文件：
	进入项目的配置文件夹中，即cd /usr/local/svndata/project_01/conf
	添加/修改用户文件，vi authz.conf
用户权限
super 最大级别的比如boss，不需要开发，所有的给读的权限
manager 项目的管理员，有读写权限，有打tag的权限
developer 开发人员，有读写权限，没打tag的权限
packer 打包人员，只有读tag的权限
======================================================
[groups]
g_super = xumoran
g_developer = ccc
 	添加/修改密码文件，vi pwd.conf
 
xumoran = xxxxxx
# sally = sallyssecret

	修改SVN项目主配置文件：
		vi /svndata/project_01/conf/svnserve.conf
		修改内容如下：
		anon-access = none：使非授权用户无法访问（此步骤必须）
		auth-access = write：使授权用户有写权限
		password-db = pwd.conf：添加密码文件
		authz-db = authz.conf：添加用户文件
		realm = project_01：添加项目名
启动SVN服务（默认端口号：3690）：
	/usr/local/svn/bin/svnserve -d -r /usr/local/svndata/
完成后进行SVN项目的检出等测试。
[检测是否启动成功]：svn co svn://182.92.156.57/project_01

提交到运行环境中：
svn checkout svn://182.92.156.57/project_01 /var/www/test/ --username xumoran --password xxxxxx
