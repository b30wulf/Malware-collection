<?php

error_reporting(0);
get_function('real_escape_string');

if(isset($_POST['submit'])){
    @array_walk($_POST, 'real_escape_string');
    $errors = '';

    foreach($_POST['type'] as $key => $item){
        if(!empty($_FILES['file']['tmp_name'][$key])){
	    $FORM_BAD = 0;
	    if(empty($item)){
		$bad_form['type'.$key] = '#' . $key . ': ' . $lang['type_empty'];
		$FORM_BAD = 1;
	    }
	    
	    if($item != '1' && $item != '2' && $item != '3' && $item != '4' && $item != '5'){
		$bad_form['type'.$key] = '#' . $key . ': ' . $lang['type_unknow'];
		$FORM_BAD = 1;
	    }
	    
	    if(@empty($_FILES['file']['tmp_name'][$key])){
		$bad_form['type'.$key] = '#' . $key . ': ' . $lang['file_empty'];
		$FORM_BAD = 1;
	    }
	    
	    if(@filesize($_FILES['file']['tmp_name'][$key]) <= 0){
		$bad_form['type'.$key] = '#' . $key . ': ' . $lang['file_empty'];
		$FORM_BAD = 1;
	    }
	    
	    if(@filesize($_FILES['file']['tmp_name'][$key]) > 1048576){
		$bad_form['type'.$key] = '#' . $key . ': ' . $lang['file_size'];
		$FORM_BAD = 1;
	    }
	    
	    $md5 = md5_file($_FILES['file']['tmp_name'][$key]);
	    $result = $mysqli->query("SELECT type, md5 FROM bf_builds WHERE (type='".$item."') AND (md5='".$md5."') LIMIT 1");
	    if(is_object($result) && $result->type == $item && $result->md5 == $md5){
		$bad_form['md5'] = '#' . $key . ': ' . $lang['file_exist'];
		$FORM_BAD = 1;
	    }
	    
	    if($FORM_BAD <> 1){
		$fname = md5($md5 . time() . mt_rand()) . '.exe';
		switch($item){
			case 1:
			    if(move_uploaded_file($_FILES['file']['tmp_name'][$key], 'cfg/' . $fname)){
				$mysqli->query('INSERT INTO `bf_builds` (`file_orig`, `md5`, `type`, `post_date`) VALUES (\''.$fname.'\', \''.$md5.'\', \''.$item.'\', CURRENT_TIMESTAMP);');
			    
				if(function_exists('save_history_log')){
				    save_history_log('Action: Add builds');
				}
			    }else{
				$errors .= '<div class="t"><div class="t4" align="center">#' . $key . ': ' . $lang['file_copy'].'</div></div>';
			    }
			break;
			
			case 2:
			case 3:
			case 4:
			case 5:
			    if(move_uploaded_file($_FILES['file']['tmp_name'][$key], 'cache/originals/' . $fname)){
				if(function_exists('save_history_log')){
				    save_history_log('Action: Add builds');
				}
				
				$mysqli->query('INSERT INTO `bf_builds` (`file_orig`, `md5`, `type`, `post_date`) VALUES (\''.$fname.'\', \''.$md5.'\', \''.$item.'\', CURRENT_TIMESTAMP);');
			    }else{
				$errors .= '<div class="t"><div class="t4" align="center">'.$lang['file_copy'].'</div></div>';
			    }
			break;
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
	}elseif(!empty($_POST['link'][$key])){
	    if(function_exists('save_history_log')){
		save_history_log('Action: Add builds');
	    }
	    
	    $mysqli->query('INSERT INTO `bf_builds` (`link`, `md5`, `type`, `post_date`) VALUES (\''.$_POST['link'][$key].'\', \''.md5($_POST['link'][$key]).'\', \''.$item.'\', CURRENT_TIMESTAMP);');
	}
    }
    
    if(empty($errors)){
	header('Location: /autosys/builds.html');
	exit;
    }
    
    $smarty->assign("errors", $errors);
}

?>