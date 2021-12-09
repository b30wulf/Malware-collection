<?php

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');

get_function('create_cfg');

if(isset($_POST['submit'])){
	if(!function_exists('ssh2_connect')){		$bad_form['ssh2'] = 'SSH2 Module not found!';
		$FORM_BAD = 1;
	}

	if(empty($_POST['adress'])){
		$bad_form['adress'] = '"' . $lang['ip'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}

	if(empty($_POST['port'])){
		$bad_form['port'] = '"' . $lang['port'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}

	if(empty($_POST['user'])){
		$bad_form['user'] = '"' . $lang['user'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}

	if(empty($_POST['pass'])){
		$bad_form['pass'] = '"' . $lang['pass'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}

	if(empty($_POST['inip'])){
		$bad_form['inip'] = '"' . $lang['inip'] . '" ' . $lang['nbp'];
		$FORM_BAD = 1;
	}else{		$inip = explode('.', $_POST['inip'], 4);
		if(count($inip) == 4){
			$inip[3] = 0;
            $_POST['inip'] = implode('.', $inip);

			foreach($inip as $it){				if($it > 255){					$bad_form['inip'] = '"' . $lang['inip'] . '" ' . $lang['nbp'];
					$FORM_BAD = 1;
					break;
				}
			}

			if($FORM_BAD != 1){
				$rinip = $mysqli->query('SELECT inip FROM bf_servers WHERE (inip = \''.$_POST['inip'].'\')');
				if($rinip->inip == $_POST['inip']){					$bad_form['inip'] = '"' . $lang['inip'] . '" ' . $lang['nbp'];
					$FORM_BAD = 1;
				}
			}
		}else{			$bad_form['inip'] = '"' . $lang['inip'] . '" ' . $lang['nbp'];
			$FORM_BAD = 1;
		}
	}

	if($FORM_BAD <> 1){
		require('classes/ssh.class.php');

		$S = new ssh();

		if(!$S->connect($_POST['adress'], $_POST['user'], $_POST['pass'], $_POST['port'])){			$errors .= '<div class="t"><div class="t4" align="center">Не могу подключится</div></div>';
		}else{			$out = $S->cmd('modprobe tun');
			if(!empty($out)){
					$errors .= '<div class="t"><div class="t4" align="center">tun нету</div></div>';
			}else{				$out = $S->cmd('cat /etc/redhat-release');
				switch(true){					case preg_match('~^(CentOS release|CentOS Linux release) 5\.([0-9])~is', $out):
						$S->cmd('yum install wget iptables zip -y');
						$S->cmd('wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-2.el5.rf.i386.rpm -O /tmp/rpmforge-release.rpm');
						$S->cmd('wget http://download.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm -O /tmp/epel-release.rpm');
						$S->cmd('wget http://dev.centos.org/centos/5/CentOS-Testing.repo -O /etc/yum.repos.d/CentOS-Testing.repo');

						$S->cmd('rpm -ivh /tmp/rpmforge-release.rpm');
						$S->cmd('rpm -ivh /tmp/epel-release.rpm');

						$S->cmd('service openvpn stop');

						$S->cmd('yum remove openvpn openvpn-* -y --skip-broken');

						$S->cmd('rm -rf /etc/openvpn/');
						$S->cmd('yum update --enablerepo=c5-testing --enablerepo=rpmforge --skip-broken -y');
						$S->cmd('yum install openvpn coreutils openssl iptables* ip* route* iproute* -y --enablerepo=rpmforge --disablerepo=c5-testing --skip-broken');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/ipt');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/ipt' . $rand);
						$S->cmd('base64 -d -i /tmp/ipt'.$rand.' > /etc/openvpn/ipt');
						$S->cmd('unlink /tmp/ipt'.$rand.'');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/server.conf');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/server.conf' . $rand);
						$S->cmd('base64 -d -i /tmp/server.conf'.$rand.' > /etc/openvpn/server.conf');
						$S->cmd('unlink /tmp/server.conf'.$rand.'');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/index.txt.attr');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/index.txt.attr' . $rand);
						$S->cmd('base64 -d -i /tmp/index.txt.attr'.$rand.' > /etc/openvpn/easy-rsa/2.0/keys/index.txt.attr');
						$S->cmd('unlink /tmp/index.txt.attr'.$rand.'');

						$S->cmd('ln -s /usr/share/doc/openvpn-2.2.0/easy-rsa/ /etc/openvpn/');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/vars');

						if(function_exists('geoip_country_code_by_name')){
							$country = geoip_country_code_by_name($_POST['adress']);
						}else{
							if(file_exists($dir . 'cache/geoip/')){
								require_once($dir . 'cache/geoip/geoip.inc');
								$gi = geoip_open($dir . 'cache/geoip/GeoIP.dat',GEOIP_STANDARD);
								$country = geoip_country_code_by_addr($gi, $_POST['adress']);
								geoip_close($gi);
								unset($gi);
								unset($record);
							}
						}
						if(empty($country)) $country = 'XX';

						$vars = $S->cmd('cat /etc/openvpn/easy-rsa/2.0/vars');
						$vars = str_replace('export KEY_CN=changeme', '#export KEY_CN=changeme', $vars);
						$vars = str_replace('export PKCS11_MODULE_PATH=', '#export PKCS11_MODULE_PATH=', $vars);
						$vars = str_replace('export PKCS11_PIN=', '#export PKCS11_PIN=', $vars);
						$ipt = str_replace('{COUNTRY}', $country, $ipt);
						$ipt = str_replace('{ORG}', 'VPNSERVER', $ipt);
						$ipt = str_replace('{RAND}', $rand, $ipt);
						$ipt = $vars . "\n\n" . $ipt;
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/vars' . $rand);
						$S->cmd('base64 -d -i /tmp/vars'.$rand.' > /etc/openvpn/easy-rsa/2.0/vars');
						$S->cmd('unlink /tmp/vars'.$rand.'');
						
						$S->cmd('mkdir /etc/openvpn/easy-rsa/2.0/keys');
						$S->cmd('chmod 777 /etc/openvpn/* -R');
						
						$sysctl = $S->cmd('cat /etc/sysctl.conf');
						$sysctl = str_replace('net.ipv4.ip_forward = 0', 'net.ipv4.ip_forward = 1', $sysctl);
						$sysctl = base64_encode($sysctl);
						$S->cmd('echo \''.$sysctl.'\' > /tmp/vars' . $rand);
						$S->cmd('base64 -d -i /tmp/vars'.$rand.' > /etc/sysctl.conf');
						$S->cmd('unlink /tmp/vars'.$rand.'');
						
						$S->cmd('echo 1 > /proc/sys/net/ipv4/conf/all/forwarding');
						
						$S->cmd('cd /etc/openvpn/easy-rsa/2.0/; ./vars; source ./vars; ./clean-all');
						$S->cmd('cd /etc/openvpn/easy-rsa/2.0/; ./vars; source ./vars; ./clean-all; export EASY_RSA="${EASY_RSA:-.}"; "$EASY_RSA/pkitool" --initca ca; "$EASY_RSA/pkitool" --server server; $OPENSSL dhparam -out /etc/openvpn/easy-rsa/2.0/keys/dh2048.pem 2048;');
						$S->cmd('chmod 777 /etc/openvpn/* -R');
						
						$S->cmd('cd /etc/openvpn/easy-rsa/2.0/; ./vars; source ./vars; export EASY_RSA="${EASY_RSA:-.}"; export KEY_EXPIRE="365"; export KEY_CN="serv1"; export KEY_NAME="serv1"; export KEY_OU="serv1"; "$EASY_RSA/pkitool" --batch serv1;');
						$S->cmd('cd /etc/openvpn/easy-rsa/2.0/; ./vars; source ./vars; export EASY_RSA="${EASY_RSA:-.}"; export KEY_EXPIRE="365"; export KEY_CN="serv2"; export KEY_NAME="serv2"; export KEY_OU="serv2"; "$EASY_RSA/pkitool" --batch serv2;');
						$S->cmd('chmod 777 /etc/openvpn/* -R');
						
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/ca.crt /etc/openvpn/ca.crt');
						//$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/ca.key /etc/openvpn/ca.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/server.key /etc/openvpn/server.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/server.crt /etc/openvpn/server.crt');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/dh2048.pem /etc/openvpn/dh2048.pem');
						$S->cmd('openvpn --genkey --secret /etc/openvpn/ta.key');
						
						$S->cmd('mkdir /etc/openvpn/clients/');
						$S->cmd('mkdir /etc/openvpn/clients/serv1');
						$S->cmd('mkdir /etc/openvpn/clients/serv2');
						$S->cmd('chmod 777 /etc/openvpn/* -R');

						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/serv1.key /etc/openvpn/clients/serv1/serv1.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/serv1.crt /etc/openvpn/clients/serv1/serv1.crt');
						$S->cmd('cp -rf /etc/openvpn/ca.crt /etc/openvpn/clients/serv1/ca.crt');
						$S->cmd('cp -rf /etc/openvpn/ta.key /etc/openvpn/clients/serv1/ta.key');
						
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/serv2.key /etc/openvpn/clients/serv2/serv2.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/serv2.crt /etc/openvpn/clients/serv2/serv2.crt');
						$S->cmd('cp -rf /etc/openvpn/ca.crt /etc/openvpn/clients/serv2/ca.crt');
						$S->cmd('cp -rf /etc/openvpn/ta.key /etc/openvpn/clients/serv2/ta.key');

						//$S->cmd('cd /etc/openvpn/clients/serv1/; tar -czvf /etc/openvpn/clients/serv1.tar ./');
						$S->cmd('cd /etc/openvpn/clients/serv1/; zip -9 -r /etc/openvpn/clients/serv1.zip ./');
						$S->cmd('cd /etc/openvpn/clients/serv2/; zip -9 -r /etc/openvpn/clients/serv2.zip ./');

						$S->cmd('/etc/openvpn/ipt');

						$S->cmd('chmod 777 /etc/openvpn/* -R');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/rc.local');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/rc.local' . $rand);
						$S->cmd('base64 -d -i /tmp/rc.local'.$rand.' >> /etc/rc.d/rc.local');
						$S->cmd('unlink /tmp/rc.local'.$rand.'');
						
						$S->cmd('chmod 777 /etc/openvpn/* -R');
						$S->cmd('chown nobody:nobody /etc/openvpn/* -R');
						$S->cmd('service openvpn start');
						
						$out = $S->cmd('base64 /etc/openvpn/clients/serv1.zip');
						
						file_put_contents('cache/serv1' . $_POST['adress'] . '.zip', base64_decode($out));
						$zip = new ZipArchive;
						$res = $zip->open('cache/serv1' . $_POST['adress'] . '.zip');

						if($res === TRUE){
							mkdir('cache/zips/' . $_POST['adress'] . '/');
							$zip->extractTo(realpath('cache/zips/' . $_POST['adress'] . '/'));
							$zip->close();
							
							$ca = file_get_contents('cache/zips/' . $_POST['adress'] . '/ca.crt');
							$crt = file_get_contents('cache/zips/' . $_POST['adress'] . '/serv1.crt');
							$key = file_get_contents('cache/zips/' . $_POST['adress'] . '/serv1.key');
							$ta = file_get_contents('cache/zips/' . $_POST['adress'] . '/ta.key');
							
							unlink('cache/zips/' . $_POST['adress'] . '/ca.crt');
							unlink('cache/zips/' . $_POST['adress'] . '/serv1.crt');
							unlink('cache/zips/' . $_POST['adress'] . '/serv1.key');
							unlink('cache/zips/' . $_POST['adress'] . '/ta.key');
							rmdir('cache/zips/' . $_POST['adress']);
							unlink('cache/serv1' . $_POST['adress'] . '.zip');
							
							//$insert_id = $mysqli->query("INSERT INTO bf_servers (`name`, `protocol`, `ip`, `port`, `ca`, `crt`, `key`, `ta`, `cfg`, `enable`) VALUES ('".$_POST['adress']."', 'udp', '".$_POST['adress']."', '15000', '".$ca."', '".$crt."', '".$key."', '".$ta."', '', '1')");
							$insert_id = $mysqli->query("INSERT INTO bf_servers (`prio`, `name`, `protocol`, `ip`, `port`, `ca`, `crt`, `key`, `ta`, `cfg`, `enable`) SELECT MAX(prio)+1, concat('".$_POST['adress']."'), concat('udp'), concat('".$_POST['adress']."'), concat('15000'), concat('".$ca."'), concat('".$crt."'), concat('".$key."'), concat('".$ta."'), concat(''), concat('1') FROM bf_servers LIMIT 1");
							
							mkdir('cfg/' . $insert_id . '/');
							
							$cfg = array();
							$cfg['id'] = $insert_id;
							$cfg['ca'] = $ca;
							$cfg['crt'] = $crt;
							$cfg['key'] = $key;
							$cfg['ta'] = $ta;
							$cfg['cfg'] = '';
							$cfg['ip'] = $_POST['adress'];
							$cfg['port'] = '15000';
							$cfg['protocol'] = 'udp';
							
							create_cfg($cfg);
						}else{
							$errors .= '<div class="t"><div class="t4" align="center">Ошибка создания ключей</div></div>';
						}
					break;

					case preg_match('~^(CentOS release|CentOS Linux release) 6\.([0-9])~is', $out):
                        /*
                    	$bit = $S->cmd('/bin/uname -m');
                        $bit = trim($bit);

                        $S->cmd('yum install wget iptables zip -y');
                    	switch($bit){                    		case 'i386':
                    		case 'i686':
								echo $S->cmd('wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-1.el6.rf.i686.rpm -O /tmp/rpmforge-release.rpm');
								echo $S->cmd('wget http://download.fedoraproject.org/pub/epel/6/i386/epel-release-6-5.noarch.rpm -O /tmp/epel-release.rpm');
                    		break;

                    		case 'x86_64':
								echo $S->cmd('wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-1.el6.rf.x86_64.rpm -O /tmp/rpmforge-release.rpm');
								echo $S->cmd('wget http://download.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-5.noarch.rpm -O /tmp/epel-release.rpm');
                    		break;
                    	}

                        $S->cmd('wget http://dev.centos.org/centos/6/testing/CentOS-Testing.repo -O /etc/yum.repos.d/CentOS-Testing.repo');

						$S->cmd('rpm -ivh /tmp/rpmforge-release.rpm');
						$S->cmd('rpm -ivh /tmp/epel-release.rpm');

						$S->cmd('service openvpn stop');

						$S->cmd('yum remove openvpn openvpn-* -y --skip-broken');

						$S->cmd('rm -rf /etc/openvpn/');
						$S->cmd('yum update --enablerepo=c6-testing --enablerepo=rpmforge --skip-broken -y');
						$S->cmd('yum install openvpn coreutils openssl iptables* ip* route* iproute* -y --enablerepo=rpmforge --disablerepo=c6-testing --skip-broken');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/ipt');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/ipt' . $rand);
						$S->cmd('base64 -d -i /tmp/ipt'.$rand.' > /etc/openvpn/ipt');
						$S->cmd('unlink /tmp/ipt'.$rand.'');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/server.conf');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/server.conf' . $rand);
						$S->cmd('base64 -d -i /tmp/server.conf'.$rand.' > /etc/openvpn/server.conf');
						$S->cmd('unlink /tmp/server.conf'.$rand.'');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/index.txt.attr');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/index.txt.attr' . $rand);
						$S->cmd('base64 -d -i /tmp/index.txt.attr'.$rand.' > /etc/openvpn/easy-rsa/2.0/keys/index.txt.attr');
						$S->cmd('unlink /tmp/index.txt.attr'.$rand.'');

						$S->cmd('ln -s /usr/share/openvpn/easy-rsa/ /etc/openvpn/');
						$S->cmd('ln -s /etc/openvpn/easy-rsa/2.0/openssl-1.0.0.cnf /etc/openvpn/easy-rsa/2.0/openssl.cnf');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/vars');

						if(function_exists('geoip_country_code_by_name')){
							$country = geoip_country_code_by_name($_POST['adress']);
						}else{
							if(file_exists($dir . 'cache/geoip/')){
								require_once($dir . 'cache/geoip/geoip.inc');
								$gi = geoip_open($dir . 'cache/geoip/GeoIP.dat',GEOIP_STANDARD);
								$country = geoip_country_code_by_addr($gi, $_POST['adress']);
								geoip_close($gi);
								unset($gi);
								unset($record);
							}
						}
						if(empty($country)) $country = 'XX';

						$vars = $S->cmd('cat /etc/openvpn/easy-rsa/2.0/vars');
						$vars = str_replace('export KEY_CN=changeme', '#export KEY_CN=changeme', $vars);
						$vars = str_replace('export PKCS11_MODULE_PATH=', '#export PKCS11_MODULE_PATH=', $vars);
						$vars = str_replace('export PKCS11_PIN=', '#export PKCS11_PIN=', $vars);
						$ipt = str_replace('{COUNTRY}', $country, $ipt);
						$ipt = str_replace('{ORG}', 'VPNSERVER', $ipt);
						$ipt = str_replace('{RAND}', $rand, $ipt);
						$ipt = $vars . "\n\n" . $ipt;
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/vars' . $rand);
						$S->cmd('base64 -d -i /tmp/vars'.$rand.' > /etc/openvpn/easy-rsa/2.0/vars');
						$S->cmd('unlink /tmp/vars'.$rand.'');
                        */

                       // echo $S->cmd('ls');
/*
	                    $rand = mt_rand();
	                    $txt = '#!/bin/bash' . "\n\n";
	                    $txt .= 'cd /etc/openvpn/easy-rsa/2.0/' . "\n";
	                    $txt .= 'mkdir /etc/openvpn/easy-rsa/2.0/keys' . "\n";
	                    $txt .= 'touch /etc/openvpn/easy-rsa/2.0/keys/index.txt' . "\n";
	                    $txt .= 'touch /etc/openvpn/easy-rsa/2.0/keys/serial' . "\n";
	                    $txt .= 'chmod 777 /etc/openvpn/* -R' . "\n";
	                    $txt .= './vars' . "\n";
	                    $txt .= './clean-all' . "\n";
	                    $txt .= 'source ./vars' . "\n";
	                    $txt .= 'export EASY_RSA="${EASY_RSA:-.}"' . "\n";
	                    $txt .= '"$EASY_RSA/pkitool" --initca ca' . "\n";
	                    $txt .= '"$EASY_RSA/pkitool" --server server' . "\n";
	                    $txt .= '$OPENSSL dhparam -out /etc/openvpn/easy-rsa/2.0/keys/dh2048.pem 2048' . "\n";
	                    $txt .= '"$EASY_RSA/pkitool" --batch serv1' . "\n";
                        $txt = base64_encode($txt);
                        $S->cmd('echo \''.$txt.'\' > /tmp/create' . $rand);
						$S->cmd('base64 -d -i /tmp/create' . $rand . ' > /tmp/create' . $rand . '.sh');
						$S->cmd('chmod 777 /tmp/create' . $rand . '.sh');
						$S->cmd('sudo /tmp/create' . $rand . '.sh');
						$S->cmd('unlink /tmp/create' . $rand);
						//$S->cmd('unlink /tmp/create' . $rand . '.sh');
                        unset($txt);
                        
	                    $S->cmd('mkdir /etc/openvpn/easy-rsa/2.0/keys');
	                    $S->cmd('touch /etc/openvpn/easy-rsa/2.0/keys/index.txt');

						$S->cmd('chmod 777 /etc/openvpn/* -R');

						$S->cmd('cd /etc/openvpn/easy-rsa/2.0/; ./vars; ./clean-all');
						$S->cmd('/etc/openvpn/easy-rsa/2.0/vars');
						$S->cmd('/etc/openvpn/easy-rsa/2.0/clean-all');
						$S->cmd('source /etc/openvpn/easy-rsa/2.0/vars');



						$S->cmd('cd /etc/openvpn/easy-rsa/2.0/; ./vars; ./clean-all; source ./vars; export EASY_RSA="${EASY_RSA:-.}"; "$EASY_RSA/pkitool" --initca ca; "$EASY_RSA/pkitool" --server server; $OPENSSL dhparam -out /etc/openvpn/easy-rsa/2.0/keys/dh2048.pem 2048; "$EASY_RSA/pkitool" --batch serv1;');

						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/ca.key /etc/openvpn/ca.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/ca.crt /etc/openvpn/ca.crt');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/server.key /etc/openvpn/server.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/server.crt /etc/openvpn/server.crt');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/dh2048.pem /etc/openvpn/dh2048.pem');
						$S->cmd('openvpn --genkey --secret /etc/openvpn/ta.key');

						$S->cmd('mkdir /etc/openvpn/clients/');
						$S->cmd('mkdir /etc/openvpn/clients/serv1');
	                    $S->cmd('chmod 777 /etc/openvpn/* -R');

						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/serv1.key /etc/openvpn/clients/serv1/serv1.key');
						$S->cmd('cp -rf /etc/openvpn/easy-rsa/2.0/keys/serv1.crt /etc/openvpn/clients/serv1/serv1.crt');
						$S->cmd('cp -rf /etc/openvpn/ca.crt /etc/openvpn/clients/serv1/ca.crt');
						$S->cmd('cp -rf /etc/openvpn/ta.key /etc/openvpn/clients/serv1/ta.key');

						$S->cmd('cd /etc/openvpn/clients/serv1/; zip -9 -r /etc/openvpn/clients/serv1.zip ./');

						$S->cmd('/etc/openvpn/ipt');

						$S->cmd('chmod 777 /etc/openvpn/* -R');

						$rand = mt_rand();
						$ipt = file_get_contents('cache/auto/rc.local');
						$ipt = str_replace('{IP}', $_POST['adress'], $ipt);
						$ipt = str_replace('{NIP}', $_POST['inip'], $ipt);
						$ipt = base64_encode($ipt);
						$S->cmd('echo \''.$ipt.'\' > /tmp/rc.local' . $rand);
						$S->cmd('base64 -d -i /tmp/rc.local'.$rand.' >> /etc/rc.d/rc.local');
						$S->cmd('unlink /tmp/rc.local'.$rand.'');

						$S->cmd('chmod 777 /etc/openvpn/* -R');
						$S->cmd('chown nobody:nobody /etc/openvpn/* -R');
						$S->cmd('service openvpn start');

						$S->cmd('unlink /etc/openvpn/clients/serv1/ca.crt');
						$S->cmd('unlink /etc/openvpn/clients/serv1/serv1.crt');
						$S->cmd('unlink /etc/openvpn/clients/serv1/serv1.key');
						$S->cmd('unlink /etc/openvpn/clients/serv1/ta.key');
						$S->cmd('rmdir /etc/openvpn/clients/serv1/');

	                    $out = $S->cmd('base64 /etc/openvpn/clients/serv1.zip');

						$S->cmd('unlink /etc/openvpn/clients/serv1.zip');

						file_put_contents('cache/serv1' . $_POST['adress'] . '.zip', base64_decode($out));
						$zip = new ZipArchive;
						$res = $zip->open('cache/serv1' . $_POST['adress'] . '.zip');

						if($res === TRUE){
							mkdir('cache/zips/' . $_POST['adress'] . '/');
							$zip->extractTo(realpath('cache/zips/' . $_POST['adress'] . '/'));
							$zip->close();

	                        $ca = file_get_contents('cache/zips/' . $_POST['adress'] . '/ca.crt');
	                        $crt = file_get_contents('cache/zips/' . $_POST['adress'] . '/serv1.crt');
	                        $key = file_get_contents('cache/zips/' . $_POST['adress'] . '/serv1.key');
	                        $ta = file_get_contents('cache/zips/' . $_POST['adress'] . '/ta.key');

	                        unlink('cache/zips/' . $_POST['adress'] . '/ca.crt');
	                        unlink('cache/zips/' . $_POST['adress'] . '/serv1.crt');
	                        unlink('cache/zips/' . $_POST['adress'] . '/serv1.key');
	                        unlink('cache/zips/' . $_POST['adress'] . '/ta.key');
	                        unlink('cache/zips/' . $_POST['adress']);
	                        unlink('cache/serv1' . $_POST['adress'] . '.zip');

	                        $insert_id = $mysqli->query("INSERT INTO bf_servers (`name`, `protocol`, `ip`, `port`, `ca`, `crt`, `key`, `ta`, `cfg`, `enable`) VALUES ('".$_POST['adress']."', 'udp', '".$_POST['adress']."', '15000', '".$ca."', '".$crt."', '".$key."', '".$ta."', '', '1')");

						    mkdir('cfg/' . $insert_id . '/');

	                        $cfg = array();
	                        $cfg['id'] = $insert_id;
	                        $cfg['ca'] = $ca;
	                        $cfg['crt'] = $crt;
	                        $cfg['key'] = $key;
	                        $cfg['ta'] = $ta;
	                        $cfg['cfg'] = '';
	                        $cfg['ip'] = $_POST['adress'];
	                        $cfg['port'] = '15000';
	                        $cfg['protocol'] = 'udp';
	                        create_cfg($cfg);
						}else{
							$errors .= '<div class="t"><div class="t4" align="center">Ошибка создания ключей</div></div>';
						}
						*/
					break;

					default:
                    	$errors .= '<div class="t"><div class="t4" align="center">Версия не поддерживается</div></div>';
					break;
				}
			}
		}
	}else{
		if(count($bad_form) > 0){
			rsort($bad_form);
			for($i = 0; $i < count($bad_form); $i++){
				if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
				$errors .= '<div class="t"><div class="t4" align="center">' . $bad_form[$i] . '</div></div>';
			}
		}
	}
	$smarty->assign("errors", $errors);
}else{	if(!isset($_POST['port'])) $_POST['port'] = 22;
	if(!isset($_POST['user'])) $_POST['user'] = 'root';

	if(!isset($_POST['inip'])) $_POST['inip'] = '10.50.100.0';
}

?>