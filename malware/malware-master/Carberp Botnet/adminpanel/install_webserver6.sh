#!/bin/sh

config=".my.cnf.$$"
command=".mysql.$$"
phpfile=".install.php.$$"
exfile="exfile.$$"
echo_c=
echo_n=
domain=
os=`/bin/uname -i`
REV=`cat /etc/redhat-release | sed s/.*release\ // | sed s/\ .*//`
REV=$(echo $REV|sed 's/.0/ /g')

if [ $os == "i386" ]; then
	echo -n "OS: i386"
elif [ $os == "x86_64" ]; then
	echo -n "OS: x86_64"
else
	echo -n "OS: Unknow"
	echo -n "...closed"
	exit;
fi

do_query() {
    /bin/echo -n "$1" > $command
    /bin/sed -i "s/all_sep/\*/" $command
#    /bin/echo -n -e '\n'
#    /bin/echo -n $1
#    /bin/echo -n -e '\n'
    /usr/bin/mysql --defaults-file=$config < $command
    return $?
}

make_config() {
    /bin/echo "[mysql]" > $config
    /bin/echo "user=root" >> $config
    /bin/echo "password=$mysql_pass" >> $config
}

do_php(){
    /bin/echo -n "<?php" > $phpfile
    /bin/echo "" >> $phpfile
    /bin/echo -n $1 >> $phpfile
    /bin/echo "" >> $phpfile
    /bin/echo -n "?>" >> $phpfile
    /usr/bin/php $phpfile
    /bin/rm  $phpfile
    return $?
}

get_domain() {
    status=1
    while [ $status -eq 1 ]; do
	stty echo
	/bin/echo $echo_n "Enter domain: $echo_c"
        read dom
        if [ "x$dom" = "x" ]; then
    	    status=1
        else
            domain=$dom
            status=0
        fi
    done
    /bin/echo
}

/usr/bin/clear

get_domain

/bin/echo $echo_n "Removal of the previous software? [Y/n] $echo_c"
read reply
if [ "$reply" = "n" ]; then
	/bin/echo " ... closed."
    exit;
fi
/bin/echo

if [ -d /var/lib/mysql/ ]; then
	/bin/echo $echo_n "Remove All database and Lighttpd files? [Y/n] $echo_c"
    read reply
    if [ "$reply" = "n" ]; then
		/bin/echo " ... closed."
		exit;
    fi
    /bin/echo
else
	if  [ -d /etc/lighttpd/ ]; then
		/bin/echo $echo_n "Remove All database and Lighttpd files? [Y/n] $echo_c"
		read reply
		if [ "$reply" = "n" ]; then
			/bin/echo " ... closed."
			exit;
		fi
		/bin/echo
    fi
fi

/usr/sbin/setenforce 0

if [ -f /usr/bin/yum ]; then
  if [ ! -d tmp/ ]; then
    /bin/mkdir tmp
  fi

  /usr/bin/yum install sed wget tar gzip libssh libssh-devel libssh2 libssh2-devel -y --skip-broken --nogpgcheck

  if [ ! -f tmp/rpmforge-release.rpm ]; then
#  	/bin/rpm -e rpmforge-release

  	if [ $os == "i386" ]; then
  		/usr/bin/wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-2.el6.rf.i686.rpm -O tmp/rpmforge-release.rpm
  	elif [ $os == "x86_64" ]; then
  		/usr/bin/wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-2.el6.rf.x86_64.rpm -O tmp/rpmforge-release.rpm
  	fi

#  	/usr/bin/yum remove rpmforge-release rpmforge -y --skip-broken
  	/bin/rpm -ivh tmp/rpmforge-release.rpm
  fi

  if [ ! -f tmp/epel-release.rpm ]; then
#  	/bin/rpm -e epel-release

  	if [ $os == "i386" ]; then
  		/usr/bin/wget http://mirror.bytemark.co.uk/fedora/epel/6/i386/epel-release-6-7.noarch.rpm -O tmp/epel-release.rpm
  	elif [ $os == "x86_64" ]; then
  		/usr/bin/wget http://mirror.bytemark.co.uk/fedora/epel/6/i386/epel-release-6-7.noarch.rpm -O tmp/epel-release.rpm
  	fi

#  	/usr/bin/yum remove epel-release epel -y --skip-broken
  	/bin/rpm -ivh tmp/epel-release.rpm
  fi

  if [ ! -f tmp/remi-release.rpm ]; then
#  	/bin/rpm -e remi-release

  	if [ $os == "i386" ]; then
  		/usr/bin/wget http://rpms.famillecollet.com/enterprise/remi-release-6.rpm -O tmp/remi-release.rpm
  	elif [ $os == "x86_64" ]; then
  		/usr/bin/wget http://rpms.famillecollet.com/enterprise/remi-release-6.rpm -O tmp/remi-release.rpm
  	fi

#  	/usr/bin/yum remove remi-release remi -y --skip-broken
  	/bin/rpm -ivh tmp/remi-release.rpm
  fi



  if [ ! -f /etc/yum.repos.d/CentOS-Testing.repo ]; then
  	/usr/bin/wget http://dev.centos.org/centos/6/testing/CentOS-Testing.repo -O /etc/yum.repos.d/CentOS-Testing.repo
  fi

  if [ ! -f tmp/phpMyAdmin.tar.gz ]; then
    if [ ! -d tmp/phpMyAdmin ]; then
		/usr/bin/wget http://downloads.sourceforge.net/project/phpmyadmin/phpMyAdmin/3.4.6/phpMyAdmin-3.4.6-all-languages.tar.gz?use_mirror=netcologne -O tmp/phpMyAdmin.tar.gz
		/bin/tar -xzf tmp/phpMyAdmin.tar.gz -C tmp/
    fi
  fi

  if [ ! -f tmp/GeoIP.dat.gz ]; then
    if [ ! -f tmp/GeoIP.dat ]; then
		/usr/bin/wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz -O tmp/GeoIP.dat.gz
		/bin/gzip -d -f tmp/GeoIP.dat.gz
    fi
  fi

  if [ ! -f tmp/GeoLiteCity.dat.gz ]; then
    if [ ! -f tmp/GeoLiteCity.dat ]; then
		/usr/bin/wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz -O tmp/GeoLiteCity.dat.gz
		/bin/gzip -d -f tmp/GeoLiteCity.dat.gz
    fi
  fi

  if [ $os == "i386" ]; then
  	if [ ! -f tmp/ioncube_loaders_lin_x86.tar.gz ]; then
  		/usr/bin/wget http://downloads2.ioncube.com/loader_downloads/ioncube_loaders_lin_x86.tar.gz -O tmp/ioncube_loaders_lin_x86.tar.gz
  		/bin/tar -xzf tmp/ioncube_loaders_lin_x86.tar.gz -C tmp/
  	fi
  elif [ $os == "x86_64" ]; then
  	if [ ! -f tmp/ioncube_loaders_lin_x86-64.tar.gz ]; then
  		/usr/bin/wget http://downloads2.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz -O tmp/ioncube_loaders_lin_x86-64.tar.gz
  		/bin/tar -xzf tmp/ioncube_loaders_lin_x86-64.tar.gz -C tmp/
  	fi
  fi

  /sbin/service mysqld stop
  /sbin/service lighttpd stop
  /sbin/service pure-ftpd stop

  /usr/bin/yum remove lighttpd lighttpd-* GeoIP GeoIP-* php php-* mysql mysql-* pure-ftpd postgresql postgresql-* apr-util apr -y --skip-broken

  /bin/rm -rf /var/lib/mysql/
  /bin/rm -rf /etc/lighttpd/
  /bin/rm -rf /etc/pure-ftpd/
  /bin/rm -f /etc/php.ini

  /usr/bin/yum update --enablerepo=c6-testing --enablerepo=remi --enablerepo=rpmforge --skip-broken -y

  /bin/echo -n '/usr/bin/yum groupinstall "Development Libraries" "Development Tools" --enablerepo=rpmforge --enablerepo=remi -y --skip-broken' > $exfile
  /bin/chmod 777 $exfile
  "./$exfile"
  /bin/rm -rf $exfile

  /usr/bin/yum install lighttpd lighttpd-fastcgi -y --enablerepo=rpmforge --disablerepo=c6-testing --disablerepo=remi --skip-broken --nogpgcheck

  /usr/bin/yum install openssh-clients GeoIP GeoIP-devel pure-ftpd sysstat -y --enablerepo=rpmforge --disablerepo=c6-testing --disablerepo=remi --skip-broken --nogpgcheck

  /usr/bin/yum install mysql mysql-server mysql-devel mysql-libs -y --enablerepo=remi --disablerepo=rpmforge --skip-broken --nogpgcheck

  /usr/bin/yum install php php-cli php-common php-devel php-gd php-mbstring php-mcrypt php-mhash php-mysql php-pear php-xml php-eaccelerator -y --enablerepo=remi --disablerepo=rpmforge --disablerepo=c6-testing --skip-broken --nogpgcheck

  /sbin/service mysqld stop
  /sbin/service mysqld start

  touch $config $command
  /bin/chmod 600 $config $command

  make_config

  do_query

  mysql_pass=`/usr/bin/tr -cd [:alnum:] < /dev/urandom | head -c16`
  user_pass=`/usr/bin/tr -cd [:alnum:] < /dev/urandom | head -c8`
  ftp_pass=`/usr/bin/tr -cd [:alnum:] < /dev/urandom | head -c8`
  ftpu_pass=`/usr/bin/tr -cd [:alnum:] < /dev/urandom | head -c8`
  user_id=`/usr/bin/id -u lighttpd`
  group_id=`/usr/bin/id -g lighttpd`

  do_query "UPDATE mysql.user SET Password=PASSWORD('$mysql_pass') WHERE User='root';"
  do_query "FLUSH PRIVILEGES;"

  make_config

  do_query "DELETE FROM mysql.user WHERE User='';"
  do_query "DELETE FROM mysql.user WHERE User='root' AND Host!='localhost';"
  do_query "DROP DATABASE test;"
  do_query "CREATE USER 'ftpuser'@'localhost' IDENTIFIED BY '$ftp_pass';"
  do_query "GRANT USAGE ON *.all_sep TO 'ftpuser'@'localhost' IDENTIFIED BY '$ftp_pass' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"
  do_query "CREATE DATABASE IF NOT EXISTS pureftpd;"
  do_query "GRANT ALL PRIVILEGES ON pureftpd.* TO 'ftpuser'@'localhost' WITH GRANT OPTION;"
  do_query "CREATE TABLE pureftpd.users ( User VARCHAR(16) BINARY NOT NULL, Password VARCHAR(64) BINARY NOT NULL, Uid INT(11) NOT NULL default '-1', Gid INT(11) NOT NULL default '-1', Dir VARCHAR(128) BINARY NOT NULL, PRIMARY KEY  (User) );"
  do_query "INSERT INTO pureftpd.users VALUES ('webserver', MD5('$ftpu_pass'), '$user_id', '$group_id', '/srv/');"
  do_query "CREATE USER 'userdb'@'localhost' IDENTIFIED BY '$user_pass';"
  do_query "GRANT FILE ON *.all_sep TO 'userdb'@'localhost' IDENTIFIED BY '$user_pass' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"
  do_query "CREATE DATABASE IF NOT EXISTS userdb;"
  do_query "GRANT ALL PRIVILEGES ON userdb.* TO 'userdb'@'localhost' WITH GRANT OPTION;"
  do_query "FLUSH PRIVILEGES;"

  /bin/rm $config $command

  /bin/chmod 777 /etc/php.ini
  /bin/chmod 777 /etc/lighttpd/* -R
  /bin/chmod 777 /etc/pure-ftpd/* -R

  do_php "

  \$modules = file_get_contents('/etc/lighttpd/modules.conf');

  \$modules = preg_replace('~#(.*)\"(mod_alias|mod_auth|mod_redirect|mod_rewrite)~', '\"\$2', \$modules);

  \$modules = str_replace('#include \"conf.d/fastcgi.conf\"', 'include \"conf.d/fastcgi.conf\"', \$modules);

  \$modules .= 'alias.url = ( \"/pma/\" => \"/srv/pma/\" )';

  file_put_contents('/etc/lighttpd/modules.conf', \$modules);

  unset(\$modules);

  \$modules = file_get_contents('/etc/lighttpd/lighttpd.conf');

  \$modules = str_replace('server.use-ipv6', '#server.use-ipv6', \$modules);

  \$modules = str_replace('#include_shell \"cat', 'include_shell \"cat', \$modules);

  file_put_contents('/etc/lighttpd/lighttpd.conf', \$modules);

  unset(\$modules);

  if(file_exists('/etc/php.ini')){
  	\$modules = file_get_contents('/etc/php.ini');

  	\$modules = str_replace(';default_charset = \"iso-8859-1\"', 'default_charset = \"utf-8\"', \$modules);

  	\$modules = str_replace('memory_limit = 32M', 'memory_limit = 512M', \$modules);

  	\$modules = str_replace('post_max_size = 8M', 'post_max_size = 32M', \$modules);

  	\$modules = str_replace('upload_max_filesize = 2M', 'upload_max_filesize = 64M', \$modules);
  	if(!empty(\$modules)) file_put_contents('/etc/php.ini', \$modules);
  }

  unset(\$modules);

  \$modules = file_get_contents('/etc/pure-ftpd/pure-ftpd.conf');

  \$modules = str_replace('# MySQLConfigFile', 'MySQLConfigFile', \$modules);

  \$modules = preg_replace('~NoAnonymous(.*)no~', 'NoAnonymous yes', \$modules);

  \$modules = preg_replace('~SyslogFacility(.*)ftp~', 'SyslogFacility none', \$modules);

  \$modules = preg_replace('~PAMAuthentication(.*)yes~', 'PAMAuthentication no', \$modules);

  \$modules = preg_replace('~MinUID(.*)500~', 'MinUID 40', \$modules);

  \$modules = str_replace('AltLog', '#AltLog', \$modules);

  \$modules = str_replace('#PIDFile', 'PIDFile', \$modules);

  \$modules = str_replace('# IPV4Only', 'IPV4Only', \$modules);

  file_put_contents('/etc/pure-ftpd/pure-ftpd.conf', \$modules);

  unset(\$modules);

  \$modules = file_get_contents('/etc/pure-ftpd/pureftpd-mysql.conf');

  \$modules = preg_replace('~MYSQLUser(.*)root~', 'MYSQLUser ftpuser', \$modules);

  \$modules = preg_replace('~MYSQLPassword(.*)rootpw~', 'MYSQLPassword $ftp_pass', \$modules);

  \$modules = preg_replace('~MYSQLCrypt(.*)cleartext~', 'MYSQLCrypt md5', \$modules);

  file_put_contents('/etc/pure-ftpd/pureftpd-mysql.conf', \$modules);

  unset(\$modules);

  "

  /bin/echo -n "

server.modules += ( \"mod_fastcgi\" )

fastcgi.server = ( \".php\" =>
                     ( \"php-num-procs\" =>
                       (
                         \"socket\" => \"/tmp/php-fastcgi-1.socket\",
                         \"bin-path\" => \"/usr/bin/php-cgi\",
                         \"bin-environment\" => ( \"PHP_FCGI_CHILDREN\" => \"8\", \"PHP_FCGI_MAX_REQUESTS\" => \"128000\"),
                         \"max-procs\" => 8,
                         \"allow-x-send-file\" => \"enable\"
                       )
                     ),
)
  " > /etc/lighttpd/conf.d/fastcgi.conf

  /usr/bin/pecl uninstall geoip
  /usr/bin/pecl install geoip

  /bin/echo -n "
extension=geoip.so
  " > /etc/php.d/geoip.ini

  /bin/cp -f tmp/GeoIP.dat /usr/share/GeoIP/GeoIP.dat
  /bin/cp -f tmp/GeoLiteCity.dat /usr/share/GeoIP/GeoIPCity.dat

  if [ $os == "i386" ]; then
  	/bin/cp -f tmp/ioncube/ioncube_loader_lin_5.3.so /usr/lib/php/modules/ioncube_loader_lin_5.3.so
  	/bin/cp -f tmp/ioncube/ioncube_loader_lin_5.3_ts.so /usr/lib/php/modules/ioncube_loader_lin_5.3_ts.so
  elif [ $os == "x86_64" ]; then
  	/bin/cp -f tmp/ioncube/ioncube_loader_lin_5.3.so /usr/lib64/php/modules/ioncube_loader_lin_5.3.so
  	/bin/cp -f tmp/ioncube/ioncube_loader_lin_5.3_ts.so /usr/lib64/php/modules/ioncube_loader_lin_5.3_ts.so
  	/bin/cp -f /usr/lib64/php/modules/geoip.so /usr/lib64/php/modules/geoip.so
  fi

  /bin/rm -rf /srv/pma/
  /bin/cp -f -r tmp/phpMyAdmin-3.4.6-all-languages/ /srv/
  /bin/mv /srv/phpMyAdmin-3.4.6-all-languages/ /srv/pma/

  /bin/chmod 777 /var/lib/php/* -R
  /bin/chmod 755 /srv/* -R

  if [ ! -d /srv/www/ ]; then
    /bin/mkdir /srv/www/
  fi

  if [ ! -d /srv/www/vhosts/ ]; then
    /bin/mkdir /srv/www/vhosts/
  fi

  if [ ! -d /srv/www/vhosts/$domain/ ]; then
    /bin/mkdir /srv/www/vhosts/$domain/
  fi

  if [ $os == "i386" ]; then
  	/bin/echo -n -e "zend_extension=\"/usr/lib/php/modules/ioncube_loader_lin_5.3.so\"\nzend_extension_ts=\"/usr/lib/php/modules/ioncube_loader_lin_5.3_ts.so\"" > /etc/php.d/ioncube.ini
  elif [ $os == "x86_64" ]; then
  	/bin/echo -n -e "zend_extension=\"/usr/lib64/php/modules/ioncube_loader_lin_5.3.so\"\nzend_extension_ts=\"/usr/lib64/php/modules/ioncube_loader_lin_5.3_ts.so\"" > /etc/php.d/ioncube.ini
  fi

  /bin/echo "" > /etc/lighttpd/vhosts.d/$domain.conf
  #/bin/echo "var.vhost = \"$domain\"" >> /etc/lighttpd/vhosts.d/$domain.conf
  /bin/echo -n "
\$HTTP[\"host\"] =~ \"(.*)\" {
    var.server_name = \"$domain\"
    server.document-root = \"/srv/www/vhosts/$domain/\"
    server.error-handler-404 = \"/srv/www/vhosts/$domain/404.html\"
    url.access-deny = ( \"lighttpd_rewrite.conf\" )
    include \"/srv/www/vhosts/$domain/lighttpd_rewrite.conf\"
}
  " >> /etc/lighttpd/vhosts.d/$domain.conf

  `openssl req -new -x509 -keyout /etc/lighttpd/server.pem -out /etc/lighttpd/server.pem -days 365 -nodes -batch`

  /bin/echo -n "
\$SERVER[\"socket\"] == \":443\" {
	ssl.engine = \"enable\"
	ssl.pemfile = \"/etc/lighttpd/server.pem\"
}
  " >> /etc/lighttpd/vhosts.d/ssl.conf

  /bin/echo -n "
url.rewrite = (
	\"^/(.*)\.(phtml|phtm|php3|inc|7z)(.*)?$\" => \"scripts/set/gateway.php?p=$1\",
	\"^/(.*)\.(cgi|pl|doc|rtf|tpl|rar)(.*)?$\" => \"scripts/get/gateway.php?p=$1\",
	\"^/(set|get)/(task|first|cab|fgr|gra|ibn|sni|scr)\.html(.*)?\" => \"scripts/$1/$2.php$3\",
    \"^/(css|images|js)/(.*)\$\" => \"templates/\$1/\$2\",
    \"^(cache|classes|crons|includes|logs|modules|scripts)/\" => \"/404.html\",
    \"^/([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)(-([0-9]+))?\.html(\?(.*))?\$\" => \"index.php?to=\$1&go=\$2&id=\$4&\$6\",
    \"^/([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)(-([A-Za-z0-9-_]+))?\.html(\?(.*))?\$\" => \"index.php?&to=\$1&go=\$2&str=\$4&\$6\",
    \"^/([a-zA-Z0-9_]+)(/(\?(.*))?)?\$\" => \"index.php?to=\$1&go=index&\$4\"
)
  " > /srv/www/vhosts/$domain/lighttpd_rewrite.conf

  /bin/chown lighttpd:lighttpd /srv/www/* -R
  /bin/chmod 755 /srv/www/* -R

  ln -s /srv/pma/ /srv/www/vhosts/$domain/

  /bin/echo "WebServer Info:" > webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "   First domain: $domain" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "MySQL Info:" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "   MySQL root password: $mysql_pass" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "   User: userdb"  >> webserver.info.txt
  /bin/echo "   User DB: userdb"  >> webserver.info.txt
  /bin/echo "   User DB host: localhost"  >> webserver.info.txt
  /bin/echo "   User DB password: $user_pass"  >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "   FTP User: ftpuser"  >> webserver.info.txt
  /bin/echo "   FTP DB: pureftpd"  >> webserver.info.txt
  /bin/echo "   FTP DB host: localhost"  >> webserver.info.txt
  /bin/echo "   FTP DB password: $ftp_pass"  >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "   PhpMyAdmin: http://you_ip/pma/ or http://$domain/pma/"  >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "FTP Info:" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "   FTP User: webserver" >> webserver.info.txt
  /bin/echo "   FTP password: $ftpu_pass" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt
  /bin/echo "" >> webserver.info.txt

  /sbin/service lighttpd start
  /sbin/service pure-ftpd start

  `/sbin/chkconfig --level 2345 lighttpd on`
  `/sbin/chkconfig --level 2345 mysqld on`
  `/sbin/chkconfig --level 2345 pure-ftpd on`

else
  /bin/echo "Yum not found"
fi
