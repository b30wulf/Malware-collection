<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
$smarty->assign('value_name', 'p' . $smarty->tpl_vars['rand_name']->value);

if(empty($Cur['id'])){
	if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить'){
		$links = explode("\n", $_POST['links']);
        if(empty($links[count($links)-1])) unset($links[count($links)-1]);

		if(!count($links) > 0){
			$bad_form['host'] = 'Не одной ссылки для добавления не найдено.';
			$FORM_BAD = 1;
		}else{			$sql = '';
			foreach($links as $key => &$link){                if(stripos($link, '.') === 0){                	unset($link);
                	unset($links[$key]);
                }

				if(!empty($link)){
					if(!stripos($link, 'http://') && !stripos($link, 'https://')) $link = 'http://' . $link;
					$link = get_host($link);
					$lins[$link] = $key;
					$sql .= '(a.host LIKE \'%'.$host.'%\') OR (b.host = \''.$host.'\') OR ';
				}
			}
			$sql = rtrim($sql, ' OR ');
			$filters = $mysqli->query('SELECT a.host host_filter, b.host host_manager from bf_filters a, bf_manager b WHERE ' . $sql, null, null, false);
			if(count($filters) > 0){
				foreach($filters as $filter){
					if(!empty($filter->host_filter)){						$filter->host = $filter->host_filter;
						if(strpos($filter->host, ',') != false){							$hosts = explode(',', $filter->host);
							if(count($hosts) > 0){
								foreach($hosts as $host){
									if(isset($lins[$host])){
										unset($links[$lins[$host]]);
									}
								}
							}
						}else{							if(isset($lins[$filter->host])){
								unset($links[$lins[$host]]);
							}
						}
					}

					if(!empty($filter->host_filter)){
						$filter->host = $filter->host_manager;
						if(isset($lins[$filter->host])){							unset($links[$lins[$host]]);
						}
					}
				}
			}
			unset($lins);
		}

		if(empty($_POST['name'])){
			$bad_form['name'] = 'Название не может быть пустым.';
			$FORM_BAD = 1;
		}

		if($FORM_BAD != 1 && count($links) > 0){			foreach($links as $link){				$mysqli->query("INSERT DELAYED INTO bf_manager (name, host, parent_id) VALUES ('".$_POST['name']."', '".$link."', '0')");
			}
			$smarty->assign('save', true);
		}else{			if(count($bad_form) > 0){
				rsort($bad_form);
				for($i = 0; $i < count($bad_form); $i++){
					if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
					$errors .= '<div align="center">' . $bad_form[$i] . '</div>';
				}
			}
		}
		$smarty->assign("errors", $errors);
	}
}else{
	$parent = $mysqli->query('SELECT * from bf_manager WHERE id = '.$Cur['id'].' LIMIT 1');

	if($parent->id != $Cur['id'] || !empty($parent->host)){
		exit;
	}

	if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить'){
		$links = explode("\n", $_POST['links']);
        if(empty($links[count($links)-1])) unset($links[count($links)-1]);

		if(!count($links) > 0){
			$bad_form['host'] = 'Не одной ссылки для добавления не найдено.';
			$FORM_BAD = 1;
		}else{
			$sql = '';
			foreach($links as $key => &$link){
                if(stripos($link, '.') === 0){
                	unset($link);
                	unset($links[$key]);
                }

				if(!empty($link)){
					if(!stripos($link, 'http://') && !stripos($link, 'https://')) $link = 'http://' . $link;
					$link = get_host($link);
					$lins[$link] = $key;
					$sql .= '(a.host LIKE \'%'.$host.'%\') OR (b.host = \''.$host.'\') OR ';
				}else{					unset($link);
                	unset($links[$key]);
				}
			}
			$sql = rtrim($sql, ' OR ');
			$filters = $mysqli->query('SELECT a.host host_filter, b.host host_manager from bf_filters a, bf_manager b WHERE ' . $sql, null, null, false);
			if(count($filters) > 0){
				foreach($filters as $filter){
					if(!empty($filter->host_filter)){
						$filter->host = $filter->host_filter;
						if(strpos($filter->host, ',') != false){
							$hosts = explode(',', $filter->host);
							if(count($hosts) > 0){
								foreach($hosts as $host){
									if(isset($lins[$host])){
										unset($links[$lins[$host]]);
									}
								}
							}
						}else{
							if(isset($lins[$filter->host])){
								unset($links[$lins[$host]]);
							}
						}
					}

					if(!empty($filter->host_filter)){
						$filter->host = $filter->host_manager;
						if(isset($lins[$filter->host])){
							unset($links[$lins[$host]]);
						}
					}
				}
			}
			unset($lins);
		}

		if(empty($_POST['name'])){
			$bad_form['name'] = 'Название не может быть пустым.';
			$FORM_BAD = 1;
		}

		if(!count($links) > 0){
			$bad_form['name'] = 'Не одной ссылки для добавления не найдено.';
			$FORM_BAD = 1;
		}

		if($FORM_BAD != 1){
			foreach($links as $link){
				$mysqli->query("INSERT DELAYED INTO bf_manager (name, host, parent_id) VALUES ('".$_POST['name']."', '".$link."', '". (empty($parent->parent_id)? $parent->id . '|' : $parent->parent_id . $parent->id . '|') ."')");
			}
			$smarty->assign('save', true);
		}else{
			if(count($bad_form) > 0){
				rsort($bad_form);
				for($i = 0; $i < count($bad_form); $i++){
					if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
					$errors .= '<div align="center">' . $bad_form[$i] . '</div>';
				}
			}
		}
		$smarty->assign("errors", $errors);
	}

	$dir['1'] = $parent->name;
	$dir['2'] = '<a href="/'.$Cur['to'].'/add_filter-'.$Cur['id'].'.html">'.$dirs['catalog']['add_filter'].'</a>';
	$smarty->assign('parent', $parent);
}

?>