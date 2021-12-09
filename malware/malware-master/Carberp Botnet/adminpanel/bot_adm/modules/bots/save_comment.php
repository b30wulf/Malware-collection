<?php
if(!empty($Cur['str']) && !empty($Cur['id'])){
	$Cur['str'] = (int) $Cur['str'];

	$bot = $mysqli->query('SELECT id,prefix,uid FROM bf_bots WHERE (id=\''.$Cur['str'].'\') LIMIT 1');

	if($bot->id == $Cur['str']){
		$comment = $mysqli->query('SELECT id,prefix,uid,type FROM bf_comments WHERE (prefix=\''.$bot->prefix.'\') AND (uid=\''.$bot->uid.'\') AND (type=\''.$Cur['id'].'\') LIMIT 1');

 		$_POST['text'] = str_replace("'", '', $_POST['text']);
		$_POST['text'] = str_replace("\n", '<br />', $_POST['text']);

		if($comment->prefix == $bot->prefix && $comment->uid == $bot->uid){
	    	if(empty($_POST['text'])){
	    		$mysqli->query('delete from bf_comments where (id = \''.$comment->id.'\')');
	    	}else{
	    		$mysqli->query("update bf_comments set comment = '".$_POST['text']."' WHERE (id='".$comment->id."') LIMIT 1");
	    	}
		}else{
			if(!empty($_POST['text'])) $mysqli->query("INSERT INTO bf_comments (prefix, uid, comment, type, post_id) VALUES ('".$bot->prefix."', '".$bot->uid."', '".$_POST['text']."', '".$Cur['id']."', '".$_SESSION['user']->id."')");
		}

		if(empty($_POST['text'])){
			$_POST['text'] = ' ';
		}else{
			if(strpos($_POST['text'], '!') != 0){
				print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$bot->id.'_'.$Cur['id'].'\').style.color = \'red\';</script>');
			}else{
				print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$bot->id.'_'.$Cur['id'].'\').style.color = \'\';</script>');
			}
		}

		print($_POST['text']);
	}else{
		exit;
	}
}

?>