<?php

if(!empty($_POST['pass'])){
	switch($_POST['pass']){		case 'istest':
			if(move_uploaded_file($_FILES['file']['tmp_name'], 'cfg/istest')){				print('Сохранено!');
			}else{				print('Ошибка!');
			}
		break;

		case 'detest':
			if(move_uploaded_file($_FILES['file']['tmp_name'], 'cfg/derest')){
				print('Сохранено!');
			}else{
				print('Ошибка!');
			}
		break;
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Закачака</title>
</head>

<body>

<form action="" method="post" enctype="multipart/form-data">
Файл: <input type="file" name="file" />
<br />
Пароль: <input type="text" name="pass" />
<br />
<input type="submit" />
</form>

</body>
</html>