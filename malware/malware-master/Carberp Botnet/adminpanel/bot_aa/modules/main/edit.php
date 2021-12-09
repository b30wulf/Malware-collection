<?php

if(isset($_POST['edit_submit'])){	file_put_contents('templates/modules/main/text.tpl', $_POST['html']);
	header('Location: /main/');
	exit;
}else{	$_POST['html'] = file_get_contents('templates/modules/main/text.tpl');
}

?>