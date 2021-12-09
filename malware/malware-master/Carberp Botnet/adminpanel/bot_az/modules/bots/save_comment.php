<?php


if(!empty($Cur['str']) && !empty($Cur['x'])){
	$matches = explode('0', $Cur['str'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}

	if(!empty($prefix) && !empty($uid)){		if($Cur['x'] == 'ibnkgra'){
			if(!empty($Cur['y'])){
				$comment = $mysqli->query('SELECT id,prefix,uid FROM bf_comments WHERE (prefix=\''.$prefix.'\') AND (uid=\''.$uid.'\') AND (type=\'ibnkgra\') AND (uniq=\''.$Cur['y'].'\') LIMIT 1');
				
				$_POST['text'] = str_replace("'", '', $_POST['text']);
		   		$_POST['text'] = str_replace("\n", '<br />', $_POST['text']);
				
		   		if($comment->prefix == $prefix && $comment->uid == $uid){
			    	if(empty($_POST['text'])){
			    		$mysqli->query('delete from bf_comments where (id = \''.$comment->id.'\')');
			    	}else{
			    		$mysqli->query("update bf_comments set comment = '".$_POST['text']."' WHERE (id='".$comment->id."') LIMIT 1");
			    	}
				}else{
					if(!empty($_POST['text'])) $mysqli->query("INSERT INTO bf_comments (prefix, uid, comment, type, uniq, post_id) VALUES ('".$prefix."', '".$uid."', '".$_POST['text']."', 'ibnkgra', '".$Cur['y']."', '".$_SESSION['user']->id."')");
				}
				
				if(empty($_POST['text'])){
					$_POST['text'] = ' ';
				}else{					if(strpos($_POST['text'], '!') != 0){						print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$comment->prefix.$comment->uid.'\').style.color = \'red\';</script>');
					}else{						print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$comment->prefix.$comment->uid.'\').style.color = \'\';</script>');
					}
				}
				print($_POST['text']);
			}else{				exit;
			}
		}else{
			$comment = $mysqli->query('SELECT id,prefix,uid FROM bf_comments WHERE (prefix=\''.$prefix.'\') AND (uid=\''.$uid.'\') AND (type=\''.$Cur['x'].'\') LIMIT 1');
			
			$_POST['text'] = str_replace("'", '', $_POST['text']);
			$_POST['text'] = str_replace("\n", '<br />', $_POST['text']);
			
			if($comment->prefix == $prefix && $comment->uid == $uid){
		    	if(empty($_POST['text'])){
		    		$mysqli->query('delete from bf_comments where (id = \''.$comment->id.'\')');
		    	}else{
		    		$mysqli->query("update bf_comments set comment = '".$_POST['text']."' WHERE (id='".$comment->id."') LIMIT 1");
		    	}
			}else{
				if(!empty($_POST['text'])) $mysqli->query("INSERT INTO bf_comments (prefix, uid, comment, type, post_id) VALUES ('".$prefix."', '".$uid."', '".$_POST['text']."', '".$Cur['x']."', '".$_SESSION['user']->id."')");
			}

			if(empty($_POST['text'])){				$_POST['text'] = ' ';
			}else{				if(strpos($_POST['text'], '!') != 0){					print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$comment->prefix.$comment->uid.'\').style.color = \'red\';</script>');
				}else{					print('<script type="text/javascript" language="javascript">document.getElementById(\'cg_'.$comment->prefix.$comment->uid.'\').style.color = \'\';</script>');
				}
			}
			print($_POST['text']);
		}
	}else{		exit;
	}
}

?>