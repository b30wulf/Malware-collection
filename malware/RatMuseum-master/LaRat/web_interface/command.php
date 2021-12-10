<?php

include "client.php";
include "parse.php";

/* 
 * This file contains all the functions (and where you implement) that are purely sent from the web console.
 */

if( isset( $_POST['command'] ) ) {
	$command = $_POST['command'];
	$clientId = isset($_POST['client_id']) ? $_POST['client_id'] : "all";
	$response = array();
	switch ($command) {
		case "getLocationHistory": {
			$response = Client::getClientLocationHistory($clientId, $_POST['number']);
			echo json_encode($response);
			break;
		}
		case 'getMessages': {
			$response = Client::getMessages($clientId);
			if($response['count'] == 0) {
				$response['status'] == 'none';
			}
			echo json_encode($response);
			break;
		}
		case 'getClients': {
			$response = Client::getClients();
			
			if ($response["count"] > 1) {
				$response['status'] = 'ok';
			} else {
				$response['status'] = 'ERROR_NO_CLIENTS';
				$response['errorMessage'] = "There are no clients that have used the application";
			}
			
			echo json_encode($response);
			
			break;
		}
		case 'getDetails': {
			$response = Client::getClientDetails($clientId);
			echo json_encode($response);
			break;
		}
		case 'getUnreadMessages': {
			$response = Client::getUnreadMessages($clientId);
			if($response['count'] == 0) {
				$response['status'] = 'none';
			}
			echo json_encode($response);
			break;
		}
		case "sendCommand": {
			Parse::sendCommand($clientId, $_POST['fn'], isset($_POST['args']) ? $_POST['args'] : "");	//json encoded arguments
			break;
		}
	}
} else {
	echo json_encode(array("status" => "failed", "error" => "A command must be supplied!"));
}

?>