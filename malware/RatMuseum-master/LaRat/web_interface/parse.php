<?php

include "ParseKeys.php";

class Parse {
	
	static function sendCommand($clientId, $function, $args) {
		$url = 'https://api.parse.com/1/push';
	
		$push_payload = json_encode(array(
			"where" => array(
					"objectId" => $clientId,
			),
			"data" => array(
					"command" => $function,
					"args" => $args
			)
		));
	
		$rest = curl_init();
		curl_setopt($rest,CURLOPT_URL,$url);
		curl_setopt($rest,CURLOPT_PORT,443);
		curl_setopt($rest,CURLOPT_POST,1);
		curl_setopt($rest,CURLOPT_POSTFIELDS,$push_payload);
		curl_setopt($rest,CURLOPT_HTTPHEADER,
			array("X-Parse-Application-Id: " . ParseKeys::$APP_ID,
					"X-Parse-REST-API-Key: " . ParseKeys::$REST_KEY,
					"Content-Type: application/json"));
		echo curl_exec($rest);
	}
}


?>