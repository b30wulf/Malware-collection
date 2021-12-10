<?php

include "client.php";
include "parse.php";

if( isset( $_POST['command'] ) ) {
	$command = $_POST['command'];
	$clientId = isset($_POST['client_id']) ? $_POST['client_id'] : "all";
	$response = array();
	switch ($command) {
		case "addMessage": {
			if (isset($_POST['client_id']) && isset($_POST['message_type']) && isset($_POST['message'])) {
				Client::addNotification($clientId, $_POST['message_type'], $_POST['message']);
				echo json_encode(array('status'=>'ok'));
			} else {
				echo json_encode(array('status' => 'failure', 'reason' => 'insufficient arguments supplied'));
			}
			break;
		}
		case "file_upload": {
			if (isset($_POST['message_type']) && isset($_POST['message'])) {
				$message = $_POST['message'];	//our base64 encoded data
				$message_type = $_POST['message_type'];
				switch ($message_type) {
					case 'IMAGE': {
						$filename = 'img/'.uniqid('img_').'.jpg';
						$file = fopen($filename, 'w');
						fwrite($file, base64_decode($message));
						fclose($file);
						Client::addNotification($clientId, 'IMG_RECV', $filename);
						echo json_encode(array('status'=>'ok'));
						break;
					}
					default:
						break;
				}
			}
			break;
		}
		case "sms_thread_list": {
			if (isset($_POST['uuid']) && isset($_POST['thread_list']) && isset($_POST['message_type'])) {
				$uuid = $_POST['uuid'];
				$thread_list = $_POST['thread_list'];
				$message_type = $_POST['message_type'];
				Client::addMessage($uuid, $message_type, $thread_list);
				echo json_encode(array('status'=>'ok'));
			} else {
				echo json_encode(array('status' => 'failure', 'reason' => 'insufficient arguments supplied'));
			}
			break;
		}
		case "sms_thread_object": {

			break;
		}
		case "updateUser": {
			Client::updateUser($clientId, array(
				'latitude' => $_POST['latitude'],
				'longitude' => $_POST['longitude']
				)
			);
			break;
		}
		case "userUpdate": {
			Client::userUpdate(
				$clientId,
				$_POST['carrier'],
				$_POST['phoneNumber'],
				$_POST['deviceid'],
				$_POST['sdkversion'],
				array(
					'latitude' => $_POST['latitude'],
					'longitude' => $_POST['longitude']
				)
			);
			echo json_encode(array("status" => "ok"));
		}
	}
} else {
	echo json_encode(array("status" => "failed", "error" => "A command must be supplied!"));
}

?>
