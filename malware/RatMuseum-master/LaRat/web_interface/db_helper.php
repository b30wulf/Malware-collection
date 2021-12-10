<?php

require('db.php');

define("DATABASE_NAME", "c4wd_clients");
define("DATABASE_USER", "c4wd_larat");
define("DATABASE_PASSWORD", "larat");

class DBHelper {

  public static $db;

  static function createDatabaseConnection() {
    self::$db = new db(DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
  }
}

?>
