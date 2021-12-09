"<?php echo $lang['dcz']; ?>"
<br /><br />
<?php
if($_GET['go'] != 'index') exit;

$INSTALL = false;

$cron_job = '1 23 * * * cd '.realpath('./crons/').'/; ./cron-24H.php &> /dev/null' . "\n";
$cron_job .= '1 10,22 * * * cd '.realpath('./crons/').'/; ./cron-12H.php &> /dev/null' . "\n";
$cron_job .= '11 0-23 * * * cd '.realpath('./crons/').'/; ./cron-60m.php &> /dev/null' . "\n";
$cron_job .= '1,31 0-23 * * * cd '.realpath('./crons/').'/; ./cron-30m.php &> /dev/null' . "\n\n";

if(!file_put_contents('cache/cron_job', $cron_job)) $INSTALL = true;

exec('cd '.realpath('./crons/').'/; ./cron-24H.php > /dev/null &');

@exec('crontab -u root ' . realpath('./') . '/cache/cron_job');
$out = exec('crontab -u root -l');

if(strpos($out, 'cron-60m.php') == false) $INSTALL = true;

?>
<br />
<?php
if($INSTALL != true){
	if($_GET['step'] == 4){
		$_SESSION['step'] = 4;
	}
?>
<?php echo $lang['zcd']; ?>
<hr />
<input type="button" value="<?php echo $lang['next']; ?>" onclick="location = '/install/index.html?step=5';" />
<?php
}else{
?>
<?php echo $lang['pn']; ?><br /><br />"<span style="color:red"><?php echo $lang['ndc']; ?></span>"!
<br /><br />
<?php echo $lang['vns']; ?>
<br /><br />
crontab -u root <?php echo realpath('./') . '/cache/cron_job'; ?>
<br /><br />
<?php echo $lang['znf']; ?>
<br /><br />
<input type="button" value="<?php echo $lang['skip']; ?>" onclick="location = '/install/index.html?step=5';" />
<?php
}
?>