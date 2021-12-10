<?php
  # Necessary at the top of every page for session management
  session_start();
  
  # Destroys the current session
  session_destroy();

  # Redirects to login page
  header("Location: login.php");
?>