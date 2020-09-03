<?php
// Sam Vuong | San Jose State University | CS-174 | Final: Lame Translate
  //==========================================================================================
  // HTTP Authentication
  require_once 'functions.php';
  require_once 'login.php';
  // Connect to mySQL database
  $conn = new mysqli($hn, $un, $pw, $db);
  // Check for query success: if failed, provide user-friendly error message and exit out of php script
  if ($conn->connect_error) die(mysql_fatal_error("Unable to establish a connection to the database!"));
  if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $un_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
    $pw_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
    $query = "SELECT * FROM users WHERE Username = '$un_temp'";
    $result = $conn->query($query);
    if (!result) die (mysql_fatal_error("Unable to retrieve table contents!")); // should change error message
    elseif ($result->num_rows) {
      $result->data_seek(0);
      $entry = $result->fetch_array(MYSQLI_ASSOC);
      $result->close();
      $salt = $entry['Salt'];
      $token = hash('ripemd128', "$salt$pw_temp");
      if ($token == $entry['Password']) {
        session_start();
        $_SESSION['username'] = $un_temp;
        $_SESSION['password'] = $pw_temp;
        // Session security
        $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        echo "You are now logged in!";
        die ("<p><a href=upload.php>Click here to continue</a></p>");
      }
      else die("Invalid username/password combination<p><br><a href=authenticate.php>Please try again.</a></p>");
    }
    else die("Invalid username/password combination<p><br><a href=authenticate.php>Please try again.</a></p>");
  }
  else {
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Please enter your username and password");
  }
  $conn->close();
?>
