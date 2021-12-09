<?php

error_reporting(0);

if(@$_GET['to'] != 'install' && @$_GET['go'] != 'index'){
	header('Location: /');
	exit;
}

if(file_exists('cache/install')){
	header('Location: /login/');
	exit;
}

session_start();

if(!empty($_GET['lang'])){
	$_SESSION['lang'] = $_GET['lang'];
	header('Location: /install/index.html?step=1');
	exit;
}

if(empty($_SESSION['lang'])) $_SESSION['lang'] = 'en';

if(!file_exists('scripts/install/language.'.$_SESSION['lang'].'.php')) $_SESSION['lang'] = 'en';
include_once('scripts/install/language.'.$_SESSION['lang'].'.php');
include('header.php');

?>

<table border="0" cellspacing="0" cellpadding="0" id="table_content">
<tr><td><?php echo $lang['install']; ?><br /><br /></td></tr>
<tr><td>
<div align="center">
<div id="main_block">

<?php

if(!empty($_GET['step'])) $_SESSION['step'] = $_GET['step'];

switch($_SESSION['step']){	case '1':
    	include('step1.php');
	break;
	case '2':
    	include('step2.php');
	break;

	case '3':
    	include('step3.php');
	break;

	case '4':
    	include('step4.php');
	break;

	case '5':
    	include('step5.php');
	break;

	default:
    	include('step0.php');
	break;
}

?>

</div>
</div>
</td></tr>
<tr><td><br /><br /><a href="http://<?php print($_SERVER['HTTP_HOST']); ?>/install/info.html" target="_blank" style="text-decoration:none;color:#000">PHPINFO</a></td></tr>
<tr><td><br /><br /><?php echo $lang['ip']; ?>: <?php print($_SERVER['REMOTE_ADDR']); ?></td></tr>
</table>

<?

include('footer.php');

?>