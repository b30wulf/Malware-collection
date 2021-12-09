"<?php echo $lang['ps']; ?>"
<br /><br />
<?php
if($_GET['go'] != 'index') exit;

$INSTALL = false;

if($_SESSION['ic'] != true){
	if(!file_exists('cache/config.json')){
		file_put_contents('cache/config.json', '{"lang":"'.$_SESSION['lang'].'","live":"30","autocmd":"0","http_post_ip":"","jabber":{"admin":"","1":{"uid":"","pass":""},"2":{"uid":"","pass":""},"tracking":"","cab":"0"},"scramb":"0","heap":"0","filters":"0","autorize_key":"0"}');
    }
}
?>
<br /><hr /><br />
<?php
if($INSTALL != true){	$_SESSION['ic'] = true;
	file_put_contents('cache/install', true);?>
"<?php echo $lang['zav']; ?>"
<br /><br />
<input type="button" value="<?php echo $lang['aut']; ?>" onclick="location = '/login';" />
<?php
}else{
?>
<?php echo $lang['pn']; ?>!
<?php
}
?>