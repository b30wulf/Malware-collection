#!/bin/sh

config=".my.cnf.$$"
command=".mysql.$$"
phpfile=".install.php.$$"
exfile="exfile.$$"
echo_c=
echo_n=
ip=
name=
dir_html=
KEY_SIZE=2048

do_php(){
    echo -n "<?php" > $phpfile
    echo "" >> $phpfile
    echo -n $1 >> $phpfile
    echo "" >> $phpfile
    echo -n "?>" >> $phpfile
    /usr/bin/php $phpfile
    rm  $phpfile
    return $?
}

get_ip() {
    status=1
    while [ $status -eq 1 ]; do
	stty echo
	echo $echo_n "Enter ip $echo_c"
        read dom
        if [ "x$dom" = "x" ]; then
    	    status=1
        else
            ip=$dom
            status=0
        fi
    done
    echo
}

get_dir() {
    status=1
    while [ $status -eq 1 ]; do
        stty echo
        echo $echo_n "Enter dir html $echo_c"
        read dom
        if [ "x$dom" = "x" ]; then
	    status=1
        else
            dir_html=$dom
            status=0
        fi
    done
    echo
}

search_rc() {
    find /etc/rc.d -name "rc.local" -type f -exec grep -l "/etc/openvpn/ipt & > /dev/null" {} \; | read i
    return $i
}

get_name() {
    status=1
    while [ $status -eq 1 ]; do
        stty echo
        echo $echo_n "Enter county/name $echo_c"
        read dom
        if [ "x$dom" = "x" ]; then
            status=1
        else
            name=$dom
            status=0
        fi
    done
    echo
}

clear

ifconfig

get_ip
get_name
get_dir

if [ -f $yum ]; then
  if [ ! -d tmp/ ]; then
    mkdir tmp
  fi

  if [ ! -f tmp/rpmforge-release.rpm ]; then
  wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-2.el5.rf.i386.rpm -O tmp/rpmforge-release.rpm
  rpm -ivh tmp/rpmforge-release.rpm
  fi

  if [ ! -f tmp/epel-release.rpm ]; then
  wget http://download.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm -O tmp/epel-release.rpm
  rpm -ivh tmp/epel-release.rpm
  fi

  if [ ! -f /etc/yum.repos.d/CentOS-Testing.repo ]; then
  wget http://dev.centos.org/centos/5/CentOS-Testing.repo -O /etc/yum.repos.d/CentOS-Testing.repo
  fi

  service openvpn stop

  echo $echo_n "Removal of the previous software? [Y/n] $echo_c"
  read reply
  if [ "$reply" = "n" ]; then
    echo " ... closed."
    exit;
  fi
  echo

  yum remove openvpn openvpn-* -y --skip-broken

  rm -rf /etc/openvpn/

  yum update --enablerepo=c5-testing --enablerepo=rpmforge --skip-broken -y

  yum install openvpn coreutils openssl iptables* ip* route* iproute* -y --enablerepo=rpmforge --disablerepo=c5-testing --skip-broken

  #do_php ""

  echo -n "#!/bin/bash

iptables -F -t nat
iptables -F -t mangle
iptables -F

ip ro flush table 100
ip ru del fwmark 100

iptables -A POSTROUTING -t nat -o tun0 -j MASQUERADE
iptables -A POSTROUTING -t nat -s 10.33.3.0/255.255.255.0 -o eth0 -j MASQUERADE

ip rule add from $ip table
ip route add table 100 via $ip dev eth0

  "  > /etc/openvpn/ipt

 echo -n "
dev tun
daemon

push \"dhcp-option DNS 8.8.8.8\"
push \"dhcp-option DNS 8.8.4.4\"

local $ip
port 15000

server 10.33.3.0  255.255.255.0

ifconfig-pool-persist /etc/openvpn/ipp.txt

persist-key
persist-tun

user nobody
group nobody

push \"redirect-gateway def1\"

tls-server
tls-auth /etc/openvpn/ta.key 0

cipher AES-256-CBC
auth SHA1
comp-lzo
keepalive 30 180

ca /etc/openvpn/ca.crt
cert /etc/openvpn/server.crt
key /etc/openvpn/server.key
dh /etc/openvpn/dh2048.pem

chroot /etc/openvpn/

" > /etc/openvpn/server.conf

ln -s /usr/share/doc/openvpn-2.2.0/easy-rsa/ /etc/openvpn/
#ln -s /etc/openvpn/easy-rsa/2.0/openssl-0.9.8.cnf /etc/openvpn/easy-rsa/2.0/openssl.cnf

echo -n "

export KEY_SIZE=$KEY_SIZE
export KEY_COUNTRY=\"$name\"
export KEY_PROVINCE=\"$name\"
export KEY_CITY=\"$name\"
export KEY_ORG=\"$name\"
export KEY_CN=\"$name\"
export KEY_NAME=\"$name\"
export KEY_OU=\"$name\"
  " >> /etc/openvpn/easy-rsa/2.0/vars

  chmod 777 /etc/openvpn/* -R

  cd /etc/openvpn/easy-rsa/2.0/
  ./vars
  ./clean-all
  source ./vars

  echo -n "unique_subject = no" > /etc/openvpn/easy-rsa/2.0/keys/index.txt.attr

  cd /etc/openvpn/easy-rsa/2.0/; ./vars; ./clean-all; source ./vars

  export EASY_RSA="${EASY_RSA:-.}"

  "$EASY_RSA/pkitool" --initca ca

  "$EASY_RSA/pkitool" --server server

  $OPENSSL dhparam -out /etc/openvpn/easy-rsa/2.0/keys/dh${KEY_SIZE}.pem ${KEY_SIZE}

  cp -rf /etc/openvpn/easy-rsa/2.0/keys/ca.key /etc/openvpn/ca.key
  cp -rf /etc/openvpn/easy-rsa/2.0/keys/ca.crt /etc/openvpn/ca.crt

  cp -rf /etc/openvpn/easy-rsa/2.0/keys/server.key /etc/openvpn/server.key
  cp -rf /etc/openvpn/easy-rsa/2.0/keys/server.crt /etc/openvpn/server.crt

  cp -rf /etc/openvpn/easy-rsa/2.0/keys/dh${KEY_SIZE}.pem /etc/openvpn/dh${KEY_SIZE}.pem

  openvpn --genkey --secret /etc/openvpn/ta.key

  /etc/openvpn/ipt

  echo search_rc

  search_rc
  ret=$?

  if [ $ret -eq 1 ]; then
    echo -n "
/etc/openvpn/ipt & > /dev/null" >> /etc/rc.d/rc.local
  fi

  mkdir /etc/openvpn/clients/

  chmod 777 /etc/openvpn/* -R

  chown nobody:nobody /etc/openvpn/* -R

  service openvpn start

  #if [ -d $dir_html/openvpn/  ]; then
    #rm -rf $dir_html/openvpn/
  #fi

  mkdir $dir_html/openvpn/

echo -n  "<?php
\$cfg = array();

\$cfg[0]['name'] = 'OpenVPN ($ip)';
\$cfg[0]['ip'] = '$ip';
\$cfg[0]['port'] = '15000';
\$cfg[0]['ca'] = 'ca';
\$cfg[0]['dir']['openvpn'] = '/etc/openvpn';
\$cfg[0]['dir']['easy-rsa'] = '/etc/openvpn/easy-rsa/2.0/';
\$cfg[0]['dir']['keys'] = '/etc/openvpn/easy-rsa/2.0/keys/';
\$cfg[0]['dir']['vars'] = '/etc/openvpn/easy-rsa/2.0/vars';
\$cfg[0]['dir']['clients'] = '/etc/openvpn/clients/';
\$cfg[0]['dir']['server'] = '/etc/openvpn/';

?>
" > config.php
  cp -f config.php $dir_html/openvpn/config.php

else
  echo "Yum not found"
fi
