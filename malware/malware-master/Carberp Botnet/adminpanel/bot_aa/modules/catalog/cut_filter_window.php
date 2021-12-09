<?php
$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

function catalog_item_load($parent){
	global $list;
	$parent_id = explode('|', $parent->parent_id);
	unset($parent_id[count($parent_id)-1]);
	$count_id = count($parent_id);
    $parent->sub = array();

	switch($count_id){
		case '0':
			$list[$parent->id] = $parent;
		break;

		case '1':
        	$list[$parent_id[0]]->sub[$parent->id] = $parent;
		break;

		case '2':
        	$list[$parent_id[0]]->sub[$parent_id[1]]->sub[$parent->id] = $parent;
		break;

		case '3':
        	$list[$parent_id[0]]->sub[$parent_id[1]]->sub[$parent_id[2]]->sub[$parent->id] = $parent;
		break;
	}
}

if(!empty($Cur['id'])){
	$item = $mysqli->query('SELECT * from bf_filters WHERE id = '.$Cur['id'].' LIMIT 1');
	if($item->id == $Cur['id'] && !empty($item->host)){		if(isset($_POST['submit']) && !empty($_POST['list'])){			$ls = $mysqli->query('SELECT id, parent_id from bf_filters WHERE (id = \''.$_POST['list'].'\') LIMIT 1');
			if($ls->id == $_POST['list']){				if($ls->parent_id == '0'){					$mysqli->query('update bf_filters set parent_id = \''.$ls->id.'|\' WHERE (id = \''.$item->id.'\')');
				}else{					$mysqli->query('update bf_filters set parent_id = \''.$ls->parent_id.$ls->id.'|\' WHERE (id = \''.$item->id.'\')');
			 	}
			 	print('<script language="javascript" type="application/javascript">document.getElementById(\'cats_content\').innerHTML = \'<br /><div align="center"><img src="/images/indicator.gif" title="Загрузка..." /></div>\'; window_close(document.getElementById(\'div_sub_'.$smarty->tpl_vars['rand_name']->value.'\').parentNode.parentNode.id, 1); hax(\'/catalog/?ajax=1\',{id: \'cats_content\',nohistory:true,nocache:true,destroy:true,onload: function (){$("#cats").treeview({animated: "fast",collapsed: true,persist: "cookie",cookieId: "logs-treeview-edit"});},rc:true})</script>');
			}
		}

        $item->parent_id = explode('|', $item->parent_id);
	    $parent = $mysqli->query('SELECT * from bf_filters WHERE id = \''.$item->parent_id[count($item->parent_id)-2].'\' LIMIT 1');
        $smarty->assign("parent", $parent);

        $smarty->assign("item", $item);

        //$list = $mysqli->query('SELECT * from bf_filters WHERE (host is NULL) AND (parent_id = \'0\')');
        $mysqli->query('SELECT * FROM bf_filters WHERE (host is NULL) ORDER by parent_id ASC', null, 'catalog_item_load');
        //print_rm($list);

		$smarty->assign('list', $list);

		$dir['1'] = $parent->name;
		$dir['2'] = '<a href="/'.$Cur['to'].'/edit_filter-'.$Cur['id'].'.html">'.$dirs['catalog']['edit_filter'].'</a>';
	}
}

?>