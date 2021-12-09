<?php

//error_reporting(-1);
get_function('real_escape_string');

function check_pid(){
    if(file_exists('cache/builds.pid')){
        $pid = file_get_contents('cache/builds.pid');
        if(stripos(exec('ps -p '.$pid), $pid) === false){
	    return false;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

$check = check_pid();
if($check != true){
	$item = $mysqli->query('SELECT * FROM bf_builds WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
	
	if($item->id == $Cur['id']){
		if(isset($_POST['submit'])){
			switch($item->type){
				case 1:
					if(empty($item->link)){
						if(move_uploaded_file($_FILES['file']['tmp_name'], 'cfg/' . $item->file_orig)){
							$mysqli->query('update bf_builds set md5 = \''.md5_file('cfg/' . $item->file_orig).'\', history = \'\', status = \'0\', file_crypt = \'\', prio = \'\', av = \'\', avt = \'\', avc = \'\', avcf = \'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$item->id.'\') LIMIT 1');
						}else{
							$errors .= '<div class="t"><div class="t4" align="center">Not Upload</div></div>';
						}
					}else{
						@unlink('cfg/' . $item->file_orig);
						@unlink('cfg/' . $item->file_crypt);
						@unlink('cache/originals/' . $item->file_orig);
						@unlink('cache/originals/' . $item->file_crypt);
						$mysqli->query('update bf_builds set md5 = \''.md5($_POST['link']).'\', link = \''.$_POST['link'].'\', history = \'\', status = \'0\', file_orig = \'\', file_crypt = \'\', prio = \'\', av = \'\', avt = \'\', avc = \'\', avcf = \'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$item->id.'\') LIMIT 1');
					}
				break;
	
				case 2:
				case 3:
				case 4:
				case 5:
					if(empty($item->link)){
						if(move_uploaded_file($_FILES['file']['tmp_name'], 'cache/originals/' . $item->file_orig)){
							$mysqli->query('update bf_builds set history = \'\', status = \'0\', file_crypt = \'\', prio = \'\', av = \'\', avt = \'\', avc = \'\', avcf = \'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$item->id.'\') LIMIT 1');
						}else{
							$errors .= '<div class="t"><div class="t4" align="center">Not Upload</div></div>';
						}
					}else{
						@unlink('cfg/' . $item->file_orig);
						@unlink('cfg/' . $item->file_crypt);
						@unlink('cache/originals/' . $item->file_orig);
						@unlink('cache/originals/' . $item->file_crypt);
						$mysqli->query('update bf_builds set md5 = \''.md5($_POST['link']).'\', link = \''.$_POST['link'].'\', history = \'\', status = \'0\', file_orig = \'\', file_crypt = \'\', prio = \'\', av = \'\', avt = \'\', avc = \'\', avcf = \'\', up_date = CURRENT_TIMESTAMP() WHERE (`id` = \''.$item->id.'\') LIMIT 1');
					}
				break;
			}
			
			if(empty($errors)){
				header('Location: /autosys/builds.html');
				exit;
			}
		}
		
		$smarty->assign('errors', $errors);
		$smarty->assign('item', $item);
	}
}

?>