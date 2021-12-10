<?php

include 'db_helper.php';

class Client {

  static function getClients() {
    $response = array();
    DBHelper::createDatabaseConnection();
    DBHelper::$db->select("clients");

    $contents = DBHelper::$db->result();

    $response["count"] = DBHelper::$db->count();
    $response["clients"] = $contents;

    return $response;
  }

  static function getClientDetails($clientId) {
    $response = array();
    DBHelper::createDatabaseConnection();
    
    if(self::userExists($clientId)) {

      DBHelper::$db->select("clients", array("objectId" => $clientId));
  
      $contents['info'] = DBHelper::$db->result();
  
      $contents['location'] = self::getClientLocationHistory($clientId, 1)['locations'];
  
      $response["clientDetails"] = $contents;
      
      $response['status'] = 'ok';
      
    } else {
      $response['status'] = 'fail';
      $response['message'] = 'Client $clientId not exist!';
    }
    
    return $response;
  }

  static function getClientLocationHistory($clientId, $count) {
    $response = array();
    DBHelper::createDatabaseConnection();

    $contents = DBHelper::$db->select("location_history", array("objectId" => $clientId), $count, "time DESC");

    $contents = DBHelper::$db->result();

    $response["locations"] = $contents;

    return $response;
  }
  
  static function userExists($objectId) {
    DBHelper::createDatabaseConnection();
    DBHelper::$db->select(
      'clients',
      array("objectId" => $objectId)
    );
    
    return DBHelper::$db->count() == 0 ? false : true;
  }
  
  static function userUpdate($objectId, $carrier, $phoneNumber, $deviceid, $sdkversion, $geoLocation) {
    DBHelper::createDatabaseConnection();
    if( !self::userExists($objectId) ) {
      DBHelper::$db->insert(
      'clients', 
      array(
          'objectId' => $objectId,
          'carrier' => $carrier,
           'deviceid' => $deviceid,
           'sdkversion' => $sdkversion,
          'phoneNumber' => $phoneNumber
        )
      );
    }
    self::updateUser($objectId, $geoLocation);
  }
  
  static function updateUser($objectId, $geoLocation) {
    DBHelper::createDatabaseConnection();
    DBHelper::$db->insert(
    'location_history', 
    array(
        'time' => date('Y-m-d H:i:s'),
        'latitude' => $geoLocation['latitude'],
        'longitude' => $geoLocation['longitude'],
        'objectId' => $objectId
      )
    );
  }
  
  static function addMessage($clientId, $messageType, $message) {
    DBHelper::createDatabaseConnection();
    DBHelper::$db->insert(
      'client_messages',
      array (
        'objectId' => $clientId,
        'message_type' => $messageType,
        'message' => $message,
        'unread' => '0',
        'time' => date('Y-m-d H:i:s')
      )
    );
  }
  
  static function addNotification($clientId, $messageType, $message) {
    DBHelper::createDatabaseConnection();
    DBHelper::$db->insert(
      'client_messages',
      array (
        'objectId' => $clientId,
        'message_type' => $messageType,
        'message' => $message,
        'unread' => '1',
        'time' => date('Y-m-d H:i:s')
      )
    );
  }
  
  static function getMessages($clientId) {
    DBHelper::createDatabaseConnection();
    $messages = DBHelper::$db->select("client_messages", (strcmp($clientId, 'all') == 0) ? false : array("objectId" => $clientId));
    $response = DBHelper::$db->result();
    $content = array('count' => DBHelper::$db->count());
    $content['messages'] = $response;
    return $content;
  }
  
  static function getUnreadMessages($clientId) {
    DBHelper::createDatabaseConnection();
    $selectStatement = (strcmp($clientId,"all") == 0) ? array("unread" => 1) :  array("objectId" => $clientId, "unread" => 1);
    $messages = DBHelper::$db->select("client_messages", $selectStatement);
    $response = DBHelper::$db->result();
    $content = array('count' => DBHelper::$db->count());
    $content['messages'] = $response;
    DBHelper::$db->update(
      'client_messages', 
      array( // fields to be updated
          'unread' => 0
      ), 
      array( // 'WHERE' clause
          'unread' => 1
      )
    );
    return $content;
  }

}

?>