"<?php echo $lang['py']; ?>"
<br /><br />
<?php
if($_GET['go'] != 'index') exit;

$INSTALL = false;

define(__DIR__, str_replace(DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'install', '', dirname(__FILE__)));

function recurse($dir){	global $INSTALL, $lang;
	if(is_file($dir)){		if(basename($dir) != '.htaccess' && basename($dir) != '.' && basename($dir) != '..'){			if(!is_writable($dir)){				if(!@chmod($dir, '777')){					$INSTALL = true;
					echo $dir . ': <span style="color:red">'.$lang['nd'].'</span><hr />';
				}
			}
		}
	}elseif(is_dir($dir)){		$d = scandir($dir);
		foreach($d as $value){			if($value != '.htaccess' && $value != '.' && $value != '..'){				if(!is_writable($dir . $value)){					if(!@chmod($dir . $value, '777')){						$INSTALL = true;
						echo $dir . $value . ': <span style="color:red">'.$lang['nd'].'</span><hr />';
					}
				}
            }

			if($value != '.' && $value != '..'){
				recurse($dir . $value . '/');
			}

		}
	}
}

recurse(__DIR__ . '/cache/');
recurse(__DIR__ . '/logs/');
recurse(__DIR__ . '/scripts/');
recurse(__DIR__ . '/crons/');
recurse(__DIR__ . '/classes/');
recurse(__DIR__ . '/modules/');
recurse(__DIR__ . '/cfg/');
recurse(__DIR__ . '/templates/');
recurse(__DIR__ . '/includes/');
recurse(__DIR__ . '/includes/config.php');

?>
<br />
<?php
if($INSTALL != true){	if($_GET['step'] == 3){		$_SESSION['step'] = 3;
	}
?>
<?php echo $lang['pf']; ?>
<hr />
<input type="button" value="<?php echo $lang['next']; ?>" onclick="location = '/install/index.html?step=3';" />
<?php
}else{
?>
<?php echo $lang['pn']; ?><br /><br />"<span style="color:red"><?php echo $lang['nk']; ?></span>"!
<?php
}
?>