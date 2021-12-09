<?php
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
$parent = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');

if(!empty($parent->id)){
	if(empty($parent->host)){
		if(isset($_POST['yes'])){
			if($parent->parent_id == 0){				$sql = 'delete from bf_filters WHERE (parent_id LIKE \''.$parent->id.'|%\') OR (id = \''.$parent->id.'\')';
			}else{				$sql = 'delete from bf_filters WHERE (parent_id LIKE \'%|'.$parent->id.'|%\') OR (id = \''.$parent->id.'\')';
			}

			$subs = $mysqli->query('SELECT * FROM bf_filters WHERE !ISNULL(host)');

			$mysqli->query($sql);
            $smarty->assign("save", true);
			//header('Location: /catalog/');
			//exit;
		}

		$dir['1'] = $parent->name;
		$dir['2'] = '<a href="/'.$Cur['to'].'/remove-'.$Cur['id'].'.html">'.$dirs['catalog']['remove_sub'].'</a>';
	}else{
        if(isset($_POST['yes'])){
			$mysqli->query('delete from bf_filters WHERE (id = \''.$parent->id.'\')');
			$mysqli->query('drop table if exists bf_filter_' . $parent->id);
            $smarty->assign("save", true);
			//header('Location: /catalog/');
			//exit;
		}

		$dir['1'] = $parent->name;
		$dir['2'] = '<a href="/'.$Cur['to'].'/remove-'.$Cur['id'].'.html">'.$dirs['catalog']['remove_filter'].'</a>';
	}

	$smarty->assign("parent", $parent);
}else{
	//header('Location: /catalog/');
	exit;
}

?>