<?php
$page['count_page'] = 25;

$clients = $mysqli->query("SELECT a.*, (SELECT COUNT(b.id) FROM bf_servers b WHERE b.client_id = a.id) server_count, (SELECT COUNT(c.id) FROM bf_admins c WHERE c.client_id = a.id) adm_count FROM bf_clients a LIMIT " . ($Cur['page'] == 0 ? 0 : $Cur['page']*$page['count_page']).','.$page['count_page'], null, null, false);

$count_keys = $mysqli->query_name("SELECT COUNT(*) count FROM bf_clients");

$smarty->assign('count_keys', $count_keys);
$smarty->assign('pages', html_pages('/clients/?', $count_keys, $page['count_page']));
$smarty->assign('clients', $clients);
$smarty->assign('title', 'Клиенты');

?>