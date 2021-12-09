<?php
get_function('html_pages');
get_function('ts2str');
include_once('modules/bots/country_code.php');

if(empty($Cur['str'])){	$Cur['str'] = 'ALL';
	$smarty->assign('title', $lang['bots'] . ' - ' . $lang['allcountr']);
}else{	$smarty->assign('title', $lang['bots'] . ' - ' . $country_code[$Cur['str']]);
}

if(isset($_POST['prefix'])){ $_SESSION['search'][$Cur['str']]['prefix'] = $_POST['prefix']; }
if(isset($_POST['ip'])) $_SESSION['search'][$Cur['str']]['ip'] = $_POST['ip'];
if(isset($_POST['life_ot'])) $_SESSION['search'][$Cur['str']]['life_ot'] = $_POST['life_ot'];
if(isset($_POST['life_do'])) $_SESSION['search'][$Cur['str']]['life_do'] = $_POST['life_do'];
if(isset($_POST['type_life'])) $_SESSION['search'][$Cur['str']]['type_life'] = $_POST['type_life'];
if(isset($_POST['sort'])) $_SESSION['search'][$Cur['str']]['sort'] = $_POST['sort'];

if(!empty($_SESSION['user']->config['prefix'])){
	$_SESSION['search'][$Cur['str']]['prefix'] = $_SESSION['user']->config['prefix'];
}

if(empty($_SESSION['search'][$Cur['str']]['type_life'])){
	$_SESSION['search'][$Cur['str']]['type_life'] = 'last_date';
}

if($_SESSION['user']->config['hunter_limit'] == true){	$_SESSION['search'][$Cur['str']]['post_id'] = $_SESSION['user']->id;
}else{	unset($_SESSION['search'][$Cur['str']]['post_id']);
}

$filter = '';
$sort = '';

if(count($_SESSION['search'][$Cur['str']])){	foreach($_SESSION['search'][$Cur['str']] as $key => $value){	    if(!empty($value)){			switch($key){
				case 'prefix':
					if(empty($filter) && $Cur['str'] == 'ALL'){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= '(prefix = \''.$value.'\') ';
				break;

				case 'ip':
	            	if(empty($filter) && $Cur['str'] == 'ALL'){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
	            	$filter .= '(ip LIKE \''.$value.'%\') ';
				break;

				case 'life_ot':
	            	if(empty($filter) && $Cur['str'] == 'ALL'){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
	            	$filter .= '('.$_SESSION['search'][$Cur['str']]['type_life'].' < \''.(time()-($_SESSION['search'][$Cur['str']]['life_ot']*60)).'\') ';
				break;

				case 'life_do':
	            	if(empty($filter) && $Cur['str'] == 'ALL'){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
	            	$filter .= '('.$_SESSION['search'][$Cur['str']]['type_life'].' > \''.(time()-($_SESSION['search'][$Cur['str']]['life_do']*60)).'\') ';
				break;

				case 'post_id':
					if(empty($filter) && $Cur['str'] == 'ALL'){ $filter = ' WHERE ';}else{$filter .= ' AND ';}
					$filter .= '(post_id = \''.$value.'\') ';
				break;

				case 'sort':
	            	switch($value){	            		case 'conn1':
	            			$sort .= ' ORDER by last_date ASC';
	            		break;

	            		case 'conn2':
	            			$sort .= ' ORDER by last_date DESC';
	            		break;

	            		case 'conn3':
	            			$sort .= ' ORDER by post_date ASC';
	            		break;

	            		case 'conn4':
	            			$sort .= ' ORDER by post_date DESC';
	            		break;
	            	}
				break;
			}
	    }
	}
}

if($Cur['str'] == 'ALL'){
	$list = $mysqli->query('SELECT id,prefix,uid,ip,last_date FROM bf_bots ' . $filter . $sort . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots_country'] : $Cur['page']*$_SESSION['user']->config['cp']['bots_country'] . ',' . $_SESSION['user']->config['cp']['bots_country']), null, null, false);
}else{	$list = $mysqli->query('SELECT id,prefix,uid,ip,last_date FROM bf_bots WHERE (country = \''.$Cur['str'].'\') ' . $filter . $sort . ' LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots_country'] : $Cur['page']*$_SESSION['user']->config['cp']['bots_country'] . ',' . $_SESSION['user']->config['cp']['bots_country']), null, null, false);
}

$list_count = count($list);
if($_SESSION['user']->config['cp']['bots_country'] <= count($list)){
	if($Cur['str'] == 'ALL') {
		$counts['alls'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots ' . $filter, null, 'count', 0, 60);
	}else{		$counts['alls'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (country = \''.$Cur['str'].'\')' . $filter, null, 'count', 0, 60);
	}
}else{
	$counts['alls'] = $list_count;
}

$smarty->assign('list', $list);
$smarty->assign('counts', $counts);
$smarty->assign('country_name', $country_code[$Cur['str']]);
$smarty->assign('pages', html_pages('/bots/country-'.$Cur['str'].'.html?', $counts['alls'], $_SESSION['user']->config['cp']['bots_country'], 1, 'bots_list', 'this.href'));
//$smarty->assign("prefix", $mysqli->query('SELECT prefix FROM bf_bots WHERE (country = \''.$Cur['str'].'\') GROUP by prefix', null, null, false));
if(empty($_SESSION['user']->config['prefix'])){
	$prefix = scandir('cache/prefix/', false);
	unset($prefix[0], $prefix[1]);
}else{
	$prefix[0] = $_SESSION['user']->config['prefix'];
}
$smarty->assign("prefix", $prefix);

if($Cur['ajax'] == 1){	print('<script type="text/javascript" language="javascript">document.title = \''.$smarty->tpl_vars['title']->value.'\';</script>');
}

?>