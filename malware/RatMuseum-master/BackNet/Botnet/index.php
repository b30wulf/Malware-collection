<?php
require("functions.php");

// Content type will always be JSON
header('Content-Type: application/json');

if(isset($_POST['data']))
{
	$data = json_decode($_POST['data']);
	if(isset($data->key) && $data->key == "{your_key_here}" && isset($data->command))
	{
		// Master is authenticated and provided a command
		ProcessMasterRequest($data);
	}
	elseif(isset($data->host_key) && isset($data->name))
	{
		// Slave gave its informations
		ProcessSlaveRequest($data->host_key, $data->name);
	}
}

?>
