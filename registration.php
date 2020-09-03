<?php
// Sam Vuong | San Jose State University | CS-174 | Final: Lame Translate
  //==========================================================================================
  // Registration
  require_once 'functions.php';
  require_once 'login.php';
  echo <<<_END
  <html><head><title>Lame Translate - Registration</title>
    <script type="text/javascript" src="validate_functions.js">
    </script>
  </head><body>
  <form method="post" action="registration.php" onsubmit="return validate_reg(this)" enctype="multipart/form-data">
    Username: <input type="text" name="username">
    <br>
    Password: <input type="text" name="password">
    <br>
    <input type="submit" value="Sign Up">
  </form></body></html>
_END;
  //==========================================================================================
  // PHP
  if (isset($_POST['username']) && isset($_POST['password'])) {
    // Connect to mySQL database
    $conn = new mysqli($hn, $un, $pw, $db);
    // Check for query success: if failed, provide user-friendly error message and exit out of php script
    if ($conn->connect_error) die(mysql_fatal_error("Unable to establish a connection to the database!"));
    $un_temp = mysql_entities_fix_string($conn, $_POST['username']);
    $pw_temp = mysql_entities_fix_string($conn, $_POST['password']);
    $query = "SELECT * FROM users WHERE Username = '$un_temp'";
    $result = $conn->query($query);
    if (!result) die (mysql_fatal_error("Unable to retrieve table contents!"));
    elseif ($result->num_rows == 0) { // Username does not already exist -- add user to table
      // Generate salt (13 characters)
      $salt = uniqid();
      // Hash the password
      $token = hash('ripemd128', "$salt$pw_temp");
      add_user($conn, $un_temp, $token, $salt);
    }
    else die ("Username is taken!");
    $result->close();
    $conn->close();
    // Return to home page
    header("Location:home.php?Message=Successfully signed up!" . urlencode($Message));
    exit;
  }
?>
